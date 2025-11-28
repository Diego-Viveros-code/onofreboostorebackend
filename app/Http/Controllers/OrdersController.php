<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DateTimeImmutable;
use DateTimeZone;
use DateInterval;
use DateTime;

class OrdersController extends Controller
{
    public function createOrder(Request $request)
    {

      
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'total'   => 'required|numeric|min:0.01',
            'items'   => 'required|array|min:1',
            'items.*.book_id'  => 'required|integer|exists:books,book_id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Orders::create([
            'user_id' => $request->user_id,
            'total'   => $request->total,
            'status'  => 'pendiente',
        ]);

        // 2. Crear los ítems de la orden
        foreach ($request->items as $item) {
            $book = Books::findOrFail($item['book_id']);

            OrdersItems::create([
                'order_id' => $order->order_id,
                'book_id'  => $book->book_id,
                'quantity' => $item['quantity'],
                'price'    => $book->price,
            ]);
        }
  

        DB::beginTransaction();

        try {
            // 1. Crear la orden
            $order = Orders::create([
                'user_id' => $request->user_id,
                'total'   => $request->total,
                'status'  => 'pendiente',
            ]);

            // 2. Crear los ítems de la orden
            foreach ($request->items as $item) {
                $book = Books::findOrFail($item['book_id']);

                OrdersItems::create([
                    'order_id' => $order->order_id,
                    'book_id'  => $book->book_id,
                    'quantity' => $item['quantity'],
                    'price'    => $book->price,
                ]);
            }

            // 3. Crear deuda en AdamsPay
            $payUrl = $this->createDebtInAdamsPay($order);

            if (!$payUrl) {
                throw new \Exception('No se pudo generar el enlace de pago en AdamsPay');
            }

            // 4. Actualizar estado y transaction_id
            $order->update([
                'status' => 'pendiente',
                'transaction_id' => 'ORDER-' . $order->order_id,
            ]);

            DB::commit();

            // Respuesta exitosa al frontend
            return response()->json([
                'message'         => 'Orden creada correctamente',
                'order_id'        => $order->order_id,
                'transaction_id'  => $order->transaction_id,
                'total'           => $order->total,
                'status'          => $order->status,
                'pay_url'         => $payUrl
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear orden con AdamsPay', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'error'   => true,
                'message' => 'No se pudo procesar tu orden. Intenta nuevamente.',
            ], 422);
        }
    }

    /**
     * Crea la deuda en AdamsPay y devuelve la URL de pago
     */
    private function createDebtInAdamsPay($order)
    {
        $config = config('services.adamspay');
        $apiUrl = $config['url'];
        $apiKey = $config['key'];
        $ifExists = $config['if_exists'] ?? 'update';

        $docId = 'ORDER-' . $order->order_id;

        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $expires = $now->add(new DateInterval('P2D'));

        $debt = [
            'docId' => $docId,
            'label' => 'Orden #' . $order->order_id . ' - Onofre Bookstore',
            'amount' => [
                'currency' => 'PYG',
                'value'    => $order->total,
            ],
            'validPeriod' => [
                'start' => $now->format(DateTime::ATOM),
                'end'   => $expires->format(DateTime::ATOM),
            ],
        ];

        try {
            $response = Http::withHeaders([
                'apikey'       => $apiKey,
                'x-if-exists'  => $ifExists,
            ])
                ->timeout(30)
                ->post($apiUrl, ['debt' => $debt]);

            if ($response->successful()) {
                $payUrl = $response->json('debt.payUrl');

                if ($payUrl) {
                    return $payUrl;
                }

                Log::warning('AdamsPay devolvió éxito pero sin payUrl', $response->json());
                return null;
            }

            // Error de la API
            Log::error('AdamsPay rechazó la deuda', [
                'status' => $response->status(),
                'body'   => $response->body(),
                'debt'   => $debt
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Excepción al conectar con AdamsPay: ' . $e->getMessage());
            return null;
        }
    }

    public function checkAdamsPayStatus($orderId)
    {
        // Buscar la orden en la BD
        $order = Orders::find($orderId);

        if (!$order) {
            return response()->json([
                'message' => 'Orden no encontrada'
            ], 404);
        }

        // Tu ID interno que enviaste a AdamsPay
        $idDeuda = 'ORDER-' . $order->order_id;

        // URL de AdamsPay
        $apiUrl = "https://staging.adamspay.com/api/v1/debts/" . $idDeuda;

        // API Key desde .env
        $apiKey = env('ADAMSPAY_API_KEY');

        // Llamada HTTP con Laravel (mucho mejor que cURL)
        $response = Http::withHeaders([
            'apikey' => $apiKey
        ])->get($apiUrl);

        // Si falló la petición
        if ($response->failed()) {
            return response()->json([
                'message' => 'Error al consultar AdamsPay',
                'details' => $response->json()
            ], 500);
        }

        $data = $response->json();

        // Si la API no devuelve deuda
        if (!isset($data['debt'])) {
            return response()->json([
                'message' => 'No se pudo obtener información de la deuda',
                'meta' => $data['meta'] ?? null
            ], 404);
        }

        $debt = $data['debt'];

        // Extraer datos importantes
        $payStatus = $debt['payStatus']['status'];
        $isPaid = $payStatus === 'paid';

        // Actualizar estado en BD
        if ($isPaid && $order->status !== 'pagado') {

            $order->status = 'pagado';
            $order->save();
        }

        return response()->json([
            'message' => 'Consulta realizada correctamente',
            'order_id' => $order->id,
            'transaction_id' => $idDeuda,
            'estado_actual' => $order->status,
            'pagado' => $isPaid,
            'adams_response' => $data
        ]);
    }

    public function getOrdersByUser($userId)
    {
        $orders = Orders::where('user_id', $userId)->get();

        return response()->json([
            'message' => 'Órdenes obtenidas correctamente',
            'orders' => $orders
        ]);
    }
}
