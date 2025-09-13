<?php

namespace App\Models\Publics;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Cart;

class CheckoutModel extends Model
{
    private $post;

    public function setOrder($post)
    {
        $this->post = $post;

        // Ambil data produk dari Cart (bukan dari $post)
        $cart = new Cart();
        $products = $cart->getRawCart(); // ['id'=>['id'=>..., 'quantity'=>...], ...]

        if (empty($products)) {
            throw new \Exception('Keranjang belanja kosong.');
        }

        // Buat ID order baru
        $order_id = DB::table('orders')->max('order_id') ?? 0;
        $this->post['order_id'] = $order_id + 1;

        DB::transaction(function () use ($products) {
            // Simpan ke tabel orders
            $id = DB::table('orders')->insertGetId([
                'order_id' => $this->post['order_id'],
                'type' => $this->post['payment_type'],
                'products' => serialize($products), // simpan array cart
                'time_created' => now(),
            ]);

            // Simpan ke tabel orders_clients
            DB::table('orders_clients')->insert([
                'for_order' => $id,
                'first_name' => htmlspecialchars(trim($this->post['first_name'] ?? '')),
                'last_name' => htmlspecialchars(trim($this->post['last_name'] ?? '')),
                'email' => htmlspecialchars(trim($this->post['email'] ?? '')),
                'phone' => htmlspecialchars(trim($this->post['phone'] ?? '')),
                'address' => htmlspecialchars(trim($this->post['address'] ?? '')),
                'city' => htmlspecialchars(trim($this->post['city'] ?? '')),
                'post_code' => htmlspecialchars(trim($this->post['post_code'] ?? '')),
                'notes' => htmlspecialchars(trim($this->post['notes'] ?? '')),
            ]);
        });

        // Kosongkan cart setelah order berhasil
        $cart->clearCart();
    }
}
