<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentUploadedNotification extends Notification
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
            ->subject("Bukti Transfer {$this->order->invoice_number} Diterima")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Bukti transfer untuk pesanan **{$this->order->invoice_number}** telah kami terima.")
            ->line('Tim kami akan memverifikasi pembayaran Anda dalam 1×24 jam kerja.')
            ->line('Anda akan mendapatkan notifikasi email setelah pembayaran dikonfirmasi.')
            ->action('Cek Status Pesanan', route('orders.show', $this->order))
            ->salutation('Terima kasih, Tim Supplier MBG');
    }
}
