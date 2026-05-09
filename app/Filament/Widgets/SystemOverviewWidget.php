<?php

namespace App\Filament\Widgets;

use App\Models\ChatbotSession;
use App\Models\User;
use App\Models\UserHistory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemOverviewWidget extends BaseWidget
{
    protected ?string $pollingInterval = '15s'; // Realtime polling

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::count())
                ->description('Pengguna terdaftar (Semua Role)')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Sesi Chatbot', ChatbotSession::count())
                ->description('Interaksi RAG dengan AI')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('success')
                ->chart([2, 5, 4, 12, 8, 15, 20]),

            Stat::make('Total Rekomendasi/Klik', UserHistory::count())
                ->description('Total aktivitas pengunjung di aplikasi')
                ->descriptionIcon('heroicon-m-cursor-arrow-rays')
                ->color('info')
                ->chart([10, 20, 50, 40, 80, 100, 120]),
        ];
    }
}
