<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification
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
            ->subject("Pembayaran {$this->order->invoice_number} Perlu Diperbaiki")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Maaf, bukti transfer untuk pesanan **{$this->order->invoice_number}** tidak dapat dikonfirmasi.")
            ->line("**Alasan:** {$this->order->rejection_reason}")
            ->line('Silakan upload ulang bukti transfer yang benar melalui tautan berikut.')
            ->action('Upload Ulang Bukti Transfer', route('orders.payment', $this->order))
            ->line('Jika ada pertanyaan, segera hubungi kami.')
            ->salutation('Tim Supplier MBG');
    }
}
