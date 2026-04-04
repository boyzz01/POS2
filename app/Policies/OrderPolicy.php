<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Order;

class OrderPolicy
{
    /** Customer can only view their own orders */
    public function view(Customer $customer, Order $order): bool
    {
        return $order->customer_id === $customer->id;
    }

    /** Upload proof only allowed when status permits and order belongs to customer */
    public function uploadProof(Customer $customer, Order $order): bool
    {
        return $order->customer_id === $customer->id && $order->canUploadProof();
    }

    /** Payment page accessible only by the order owner */
    public function viewPayment(Customer $customer, Order $order): bool
    {
        return $order->customer_id === $customer->id;
    }
}
