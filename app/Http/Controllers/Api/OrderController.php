<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        try {
            $order = DB::transaction(function () use ($request, $user) {
                $totalPrice = 0;
                $orderItemsData = []; // Ubah nama variabel biar jelas

                foreach ($request->items as $item) {
                    // Lock for Update agar stok aman
                    $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

                    if ($product->stock < $item['quantity']) {
                        throw ValidationException::withMessages([
                            'items' => ["Stok untuk produk {$product->name} tidak cukup. Sisa: {$product->stock}"],
                        ]);
                    }

                    // Kurangi Stok
                    $product->decrement('stock', $item['quantity']);

                    $totalPrice += $product->price * $item['quantity'];

                    // Siapkan array data
                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price_at_purchase' => $product->price,
                    ];
                }

                // Buat Order
                $order = $user->orders()->create([
                    'invoice_number' => 'INV-' . time() . '-' . $user->id, // PERBAIKAN: invoice_code (bukan number)
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);

                // Masukkan Item
                // PERBAIKAN: items() (sesuai nama fungsi di Model Order), bukan orderItems()
                $order->items()->createMany($orderItemsData);

                return $order;
            });

            return response()->json([
                'message' => 'Order berhasil dibuat',
                'data' => $order->load('items')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Transaksi gagal',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}