<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class OrderPolicy
{
    /** Admins (User model) bypass all checks */
    public function before(Authenticatable $user, string $ability): ?bool
    {
        if ($user instanceof User) {
            return true;
        }

        return null; // defer to individual methods for customers
    }

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
