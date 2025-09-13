<?php

namespace App\Http\Controllers\Publics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart;

class CartController extends Controller
{
    private $cart;

    public function __construct()
    {
        $this->cart = new Cart();
    }

    public function addProduct(Request $request)
    {
        $post = $request->all();
        $quantity = isset($post['quantity']) ? (int)$post['quantity'] : 1;
        if ($quantity <= 0) {
            $quantity = 1;
        }

        if (!isset($post['id'])) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Product ID missing']);
            }
            return redirect()->back()->with('error', 'Product ID missing');
        }

        $this->cart->addProduct($post['id'], $quantity);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function removeProductQuantity(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $post = $request->all();
        $this->cart->removeProductQuantity($post['id']);
    }

    public function removeProduct(Request $request)
    {
        // Bisa akses dari POST biasa (bukan AJAX)
        $post = $request->all();

        if (!isset($post['id'])) {
            return redirect()->back()->with('error', 'Product ID missing');
        }

        $this->cart->removeProduct($post['id']);

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}
