<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Notifications\PaymentApprovedNotification;
use App\Notifications\PaymentRejectedNotification;
use App\Services\OrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Order $order */
        $order   = $this->record;
        $actions = [];

        if ($order->status === OrderStatus::PaymentUploaded) {
            $actions[] = Action::make('approve')
                ->label('Konfirmasi Pembayaran')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Pembayaran')
                ->modalDescription("Tandai pembayaran order {$order->invoice_number} sebagai LUNAS?")
                ->action(function () use ($order) {
                    app(OrderService::class)->approvePayment($order);

                    try {
                        $order->customer->notify(new PaymentApprovedNotification($order));
                    } catch (\Throwable) {}

                    $this->refreshFormData(['status', 'paid_at']);

                    Notification::make()->title('Pembayaran dikonfirmasi!')->success()->send();
                });

            $actions[] = Action::make('reject')
                ->label('Tolak Pembayaran')
                ->icon(Heroicon::OutlinedXCircle)
                ->color('danger')
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->minLength(10)
                        ->placeholder('Contoh: Nominal transfer tidak sesuai.')
                        ->rows(3),
                ])
                ->modalHeading('Tolak Pembayaran')
                ->action(function (array $data) use ($order) {
                    app(OrderService::class)->rejectPayment($order, $data['rejection_reason']);

                    try {
                        $order->customer->notify(new PaymentRejectedNotification($order));
                    } catch (\Throwable) {}

                    $this->refreshFormData(['status', 'rejection_reason']);

                    Notification::make()->title('Pembayaran ditolak.')->warning()->send();
                });
        }

        if ($order->status === OrderStatus::Paid) {
            $actions[] = Action::make('complete')
                ->label('Tandai Selesai')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->color('info')
                ->requiresConfirmation()
                ->action(function () use ($order) {
                    $order->update(['status' => OrderStatus::Completed]);
                    $this->refreshFormData(['status']);
                    Notification::make()->title('Pesanan ditandai selesai.')->success()->send();
                });
        }

        if ($order->latestPaymentProof) {
            $proof     = $order->latestPaymentProof;
            $actions[] = Action::make('view_proof')
                ->label('Lihat Bukti Transfer')
                ->icon(Heroicon::OutlinedEye)
                ->color('info')
                ->url(route('orders.payment.proof', [$order, $proof]))
                ->openUrlInNewTab();
        }

        return $actions;
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->components([
                Section::make('Informasi Pesanan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('invoice_number')
                                ->label('Invoice')
                                ->weight('bold')
                                ->copyable(),

                            TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                                ->color(fn (OrderStatus $state): string => $state->color()),

                            TextEntry::make('created_at')
                                ->label('Tanggal Pesan')
                                ->dateTime('d M Y, H:i'),

                            TextEntry::make('total')
                                ->label('Total Pembayaran')
                                ->money('IDR')
                                ->weight('bold')
                                ->color('warning'),

                            TextEntry::make('paid_at')
                                ->label('Tanggal Lunas')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('Belum lunas'),

                            TextEntry::make('rejection_reason')
                                ->label('Alasan Penolakan')
                                ->placeholder('—')
                                ->visible(fn (Order $record) => $record->status === OrderStatus::Rejected),
                        ]),
                    ]),

                Section::make('Data Pemesan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('customer_name')->label('Nama Pemesan'),
                            TextEntry::make('customer_phone')->label('WhatsApp'),
                            TextEntry::make('customer.email')->label('Email Customer'),
                            TextEntry::make('customer_address')->label('Alamat Pengiriman')->columnSpanFull(),
                            TextEntry::make('notes')->label('Catatan')->placeholder('—')->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Item Pesanan')
                    ->schema([
                        TextEntry::make('items')
                            ->label('')
                            ->state(function (Order $record): string {
                                return $record->items->map(fn ($item) =>
                                    "• {$item->product_name} ({$item->product_size}) "
                                    . "× {$item->quantity} karton "
                                    . "@ Rp " . number_format($item->price_per_unit, 0, ',', '.')
                                    . " = Rp " . number_format($item->subtotal, 0, ',', '.')
                                )->implode("\n");
                            })
                            ->columnSpanFull(),

                        Grid::make(3)->schema([
                            TextEntry::make('subtotal')->label('Subtotal')->money('IDR'),
                            TextEntry::make('shipping_cost')->label('Ongkir')->money('IDR'),
                            TextEntry::make('total')->label('Total')->money('IDR')->weight('bold'),
                        ]),
                    ]),

                Section::make('Info Bank')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('bank_name')->label('Bank'),
                            TextEntry::make('bank_account')->label('Nomor Rekening')->copyable(),
                            TextEntry::make('bank_holder')->label('Atas Nama'),
                        ]),
                    ]),
            ]);
    }
}
