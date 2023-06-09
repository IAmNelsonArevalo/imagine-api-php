<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderProduct};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB};

class OrdersController extends Controller
{
    public function createOrder(Request $request): array
    {
        $status = false;
        $result = null;
        $order = null;
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->user_id = $request->user;
            $order->address = $request->address;
            $order->latitude = $request->latitude;
            $order->longitude = $request->longitude;
            $order->comments = $request->comments;
            $order->status_id = $this->createdOrder;
            $order->save();

            foreach ($request->products as $product) {
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->reference_id = $product['reference'];
                $orderProduct->quantity = $product['quantity'];
                $orderProduct->price = $product['price'];
                $orderProduct->save();
            }

            $status = true;
            DB::commit();
        } catch (\Throwable | \Exception $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, ['type' => 'success', 'content' => 'Order created.'], $order);
        } else {
            return $this->responseApi(false, ['type' => 'error', 'content' => 'Error creating order.'], $result);
        }
    }

    public function changeStatusOrder(Request $request): array
    {
        $status = false;
        $result = null;
        $order = null;
        DB::beginTransaction();
        try {
            $order = Order::find($request->id);
            $order->status_id = $request->status;
            $order->save();

            $status = true;
            DB::commit();
        } catch (\Throwable | \Exception $th) {
            $result = $th->getMessage();
            DB::rollBack();
        }

        if ($status) {
            return $this->responseApi(true, ['type' => 'success', 'content' => 'Status order changed.'], $order);
        } else {
            return $this->responseApi(false, ['type' => 'error', 'content' => 'Error changing the status order.'], $result);
        }
    }
}
