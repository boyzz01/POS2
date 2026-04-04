<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = auth('customer')->user()
            ->orders()
            ->with('items', 'latestPaymentProof')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->customer_id === auth('customer')->id(), Response::HTTP_FORBIDDEN);

        $order->load('items.product', 'paymentProofs');

        return view('orders.show', compact('order'));
    }

    public function payment(Order $order): View
    {
        abort_unless($order->customer_id === auth('customer')->id(), Response::HTTP_FORBIDDEN);

        $order->load('items', 'latestPaymentProof');

        return view('orders.payment', compact('order'));
    }
}
