<?php

namespace App\Http\Controllers\Publics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart;
use App\Models\Publics\ProductsModel;
use App\Models\Publics\CheckoutModel;
use Lang;
use DB;

class CheckoutController extends Controller
{
    protected $products;

    public function __construct()
    {
        $cart = new Cart();
        $this->products = $cart->getCartProducts();
    }

    public function index()
    {
        $cartProducts = $this->products;
        $productIds = collect($cartProducts)->pluck('id')->toArray();

        $productsModel = new ProductsModel();

        // Ambil nama produk di keranjang
        $productNames = $productsModel->getProductNamesByIds($productIds);

        // Cari rekomendasi produk berdasar association_rules (baik A => B atau B => A)
        $recommendationNamesA = DB::table('association_rules')
            ->whereIn('product_a', $productNames)
            ->pluck('product_b')
            ->toArray();

        $recommendationNamesB = DB::table('association_rules')
            ->whereIn('product_b', $productNames)
            ->pluck('product_a')
            ->toArray();

        $recommendationNames = array_unique(array_merge($recommendationNamesA, $recommendationNamesB));

        $recommendedProducts = $productsModel->getProductsByNames($recommendationNames);

        return view('publics.checkout', [
            'cartProducts' => $cartProducts,
            'recommendedProducts' => $recommendedProducts,
            'head_title' => Lang::get('seo.title_checkout'),
            'head_description' => Lang::get('seo.descr_checkout')
        ]);
    }

    public function setOrder(Request $request)
    {
        $post = $request->all();

        // Pastikan id dan quantity ada dan berupa array
        $ids = $post['id'] ?? [];
        $quantities = $post['quantity'] ?? [];

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        if (!is_array($quantities)) {
            $quantities = [$quantities];
        }

        // Buat data baru untuk dikirim ke model supaya aman
        $data = $post;
        $data['id'] = $ids;
        $data['quantity'] = $quantities;

        $checkoutModel = new CheckoutModel();
        $checkoutModel->setOrder($data);

        $cart = new Cart();
        $cart->clearCart();

        return redirect(lang_url('/'))->with([
            'msg' => Lang::get('public_pages.order_accepted'),
            'result' => true
        ]);
    }
}
