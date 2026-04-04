<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
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
            ->subject("Pesanan {$this->order->invoice_number} Berhasil Dibuat")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Pesanan Anda dengan nomor **{$this->order->invoice_number}** telah berhasil dibuat.")
            ->line("**Total Pembayaran:** Rp " . number_format($this->order->total, 0, ',', '.'))
            ->line("Silakan segera lakukan transfer ke:")
            ->line("Bank **{$this->order->bank_name}** - {$this->order->bank_account} a.n. {$this->order->bank_holder}")
            ->action('Lihat Halaman Pembayaran', route('orders.payment', $this->order))
            ->line('Setelah transfer, upload bukti pembayaran pada halaman tersebut.')
            ->salutation('Terima kasih, Tim Supplier MBG');
    }
}
