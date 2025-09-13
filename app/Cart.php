<?php

namespace App;

use App\Models\Publics\ProductsModel;

class Cart
{
    private $cookieExpTime = 2678400; // 1 bulan
    public $countProducts = 0;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION['laraCart'])) {
            $_SESSION['laraCart'] = [];
        }

        // Restore dari cookie jika session kosong
        if (empty($_SESSION['laraCart']) && isset($_COOKIE['laraCart'])) {
            $_SESSION['laraCart'] = unserialize($_COOKIE['laraCart'], ["allowed_classes" => false]);
        }
    }

    /**
     * Tambah produk ke cart
     */
    public function addProduct($id, $quantity)
    {
        if ($quantity <= 0) {
            $quantity = 1;
        }

        if (isset($_SESSION['laraCart'][$id])) {
            $_SESSION['laraCart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['laraCart'][$id] = [
                'id' => (int)$id,
                'quantity' => $quantity
            ];
        }

        $this->updateCookie();
    }

    /**
     * Kurangi quantity 1 produk
     */
    public function removeProductQuantity($id)
    {
        if (isset($_SESSION['laraCart'][$id])) {
            $_SESSION['laraCart'][$id]['quantity']--;
            if ($_SESSION['laraCart'][$id]['quantity'] <= 0) {
                unset($_SESSION['laraCart'][$id]);
            }
        }

        $this->updateCookie();
    }

    /**
     * Hapus produk sepenuhnya dari cart
     */
    public function removeProduct($id)
    {
        if (isset($_SESSION['laraCart'][$id])) {
            unset($_SESSION['laraCart'][$id]);
        }

        $this->updateCookie();
    }

    /**
     * Bersihkan seluruh cart
     */
    public function clearCart()
    {
        $_SESSION['laraCart'] = [];
        setcookie('laraCart', null, -1, '/');
    }

    /**
     * Ambil semua produk dari cart (dengan info detail dari DB)
     */
    public function getCartProducts()
    {
        $productsModel = new ProductsModel();
        $cart = $_SESSION['laraCart'] ?? [];

        $products = [];
        if (!empty($cart)) {
            $ids = array_keys($cart);
            $products = $productsModel->getProductsWithIds($ids);

            foreach ($products as &$product) {
                $product->num_added = $cart[$product->id]['quantity'];
            }
        }

        $this->countProducts = array_sum(array_column($cart, 'quantity'));
        return $products;
    }

    /**
     * Untuk menampilkan cart di popup / halaman
     */
    public function getCartHtmlWithProducts()
    {
        $products = $this->getCartProducts();

        if (!empty($products)) {
            ob_start();
            include '../resources/views/publics/cartHtml.php';
            return ob_get_clean();
        }

        return '';
    }

    /**
     * Untuk halaman checkout
     */
    public function getCartHtmlWithProductsForCheckoutPage()
    {
        $products = $this->getCartProducts();

        if (!empty($products)) {
            ob_start();
            include '../resources/views/publics/cartHtmlForCheckoutPage.php';
            return ob_get_clean();
        }

        return '';
    }

    /**
     * Helper untuk update cookie setiap ada perubahan cart
     */
    private function updateCookie()
    {
        setcookie('laraCart', serialize($_SESSION['laraCart']), time() + $this->cookieExpTime, '/');
    }

    /**
     * Ambil array asli cart (untuk proses checkout)
     */
    public function getRawCart()
    {
        return $_SESSION['laraCart'] ?? [];
    }
}
