<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPaymentProofRequest;
use App\Models\Order;
use App\Models\PaymentProof;
use App\Services\PaymentProofService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentProofController extends Controller
{
    public function __construct(private readonly PaymentProofService $service) {}

    public function upload(UploadPaymentProofRequest $request, Order $order): RedirectResponse
    {
        $this->authorize('uploadProof', $order);

        $this->service->upload($order, $request->file('proof'));

        return redirect(route('orders.payment', $order))
            ->with('success', 'Bukti transfer berhasil dikirim. Mohon tunggu konfirmasi dari admin.');
    }

    /**
     * Serve private payment proof file with authorization check.
     * Only the order owner or admins can view/download the file.
     */
    public function show(Order $order, PaymentProof $proof): StreamedResponse
    {
        // Ensure the proof belongs to this order
        abort_unless($proof->order_id === $order->id, 404);

        $this->authorize('view', $order);

        return $this->service->streamProof($proof);
    }
}
