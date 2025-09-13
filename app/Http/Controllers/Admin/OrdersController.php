<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang;
use App\Models\Admin\OrdersModel;
use App\Models\Admin\ProductsModel;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $ordersModel = new OrdersModel();
        $orders = $ordersModel->getOrders();
        $fastOrders = $ordersModel->getFastOrders();
        return view('admin.orders', [
            'page_title_lang' => Lang::get('admin_pages.orders'),
            'orders' => $orders,
            'fastOrders' => $fastOrders,
            'controller' => $this
        ]);
    }

    public function changeStatus(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $orderId = $request->input('order_id');
        $status = $request->input('order_value');

        // Update status via model
        $ordersModel = new OrdersModel();
        $ordersModel->setNewStatus($request->all());

        // Jika status complete, kurangi stok
        if ((int)$status === 3) {
            $order = $ordersModel->getOrderById($orderId); // Buat method ini di OrdersModel
            if ($order && isset($order->products)) {
                $products = unserialize($order->products);
                $productsModel = new ProductsModel();

                foreach ($products as $product) {
                    $p = $productsModel->getProduct($product['id']);
                    if ($p) {
                        $newQty = max(0, $p->quantity - $product['quantity']);
                        $productsModel->updateQuantity($p->id, $newQty);
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function getProductInfo($id)
    {
        $productsModel = new ProductsModel();
        return $productsModel->getProduct($id);
    }

    public function markFastOrder(Request $request)
    {
        if (isset($request->id) && (int)$request->id > 0) {
            $ordersModel = new OrdersModel();
            $ordersModel->setFastOrderAsViewed($request->id);
            return redirect(lang_url('admin/orders'))->with([
                'msg' => Lang::get('admin_pages.fast_order_marked'),
                'result' => true
            ]);
        } else {
            abort(404);
        }
    }
    public function exportApriori()
    {
        $ordersModel = new OrdersModel();
        $orders = \DB::table('orders')->get();

        $txtContent = '';

        foreach ($orders as $order) {
            if (!isset($order->products)) continue;

            $products = unserialize($order->products);
            $productNames = [];

            foreach ($products as $product) {
                $productInfo = $this->getProductInfo($product['id']);
                if ($productInfo) {
                    $productNames[] = $productInfo->name;
                }
            }

            if (!empty($productNames)) {
                $txtContent .= implode(',', $productNames) . "\n";
            }
        }

        $fileName = 'orders_apriori_' . date('Ymd_His') . '.txt';

        return response($txtContent)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename=' . $fileName);
    }


    /**
     * Helper untuk ambil info produk
     */
}
