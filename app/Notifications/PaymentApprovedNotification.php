<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Pembayaran {$this->order->invoice_number} Dikonfirmasi ✓")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Selamat! Pembayaran untuk pesanan **{$this->order->invoice_number}** telah **dikonfirmasi**.")
            ->line("**Total:** Rp " . number_format($this->order->total, 0, ',', '.'))
            ->line('Pesanan Anda sedang kami proses untuk pengiriman.')
            ->action('Lihat Detail Pesanan', route('orders.show', $this->order))
            ->salutation('Terima kasih telah memesan, Tim Supplier MBG');
    }
}
