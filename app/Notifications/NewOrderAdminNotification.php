<?php

namespace App\Notifications;

use App\Models\Order;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderAdminNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Pesanan baru masuk')
            ->body("#{$this->order->invoice_number} dari {$this->order->customer_name}")
            ->icon('heroicon-o-shopping-bag')
            ->iconColor('warning')
            ->getDatabaseMessage();
    }
}
