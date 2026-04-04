<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
        $this->authorize('view', $order);

        $order->load('items.product', 'paymentProofs');

        return view('orders.show', compact('order'));
    }

    public function payment(Order $order): View
    {
        $this->authorize('viewPayment', $order);

        $order->load('items', 'latestPaymentProof');

        return view('orders.payment', compact('order'));
    }
}
