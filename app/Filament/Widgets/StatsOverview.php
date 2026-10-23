<?php

namespace App\Filament\Widgets;

use App\Models\ArticleNews;
use App\Models\Author;
use App\Models\BannerAdvertisement;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Artikel', ArticleNews::count())
                ->description('Artikel tayang')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary'),

            Stat::make('Artikel Unggulan', ArticleNews::where('is_featured', 'featured')->count())
                ->description('Tampil di carousel')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Penulis', Author::count())
                ->description('Terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('gray'),

            Stat::make('Kategori', Category::count())
                ->description('Terdaftar')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('gray'),

            Stat::make('Banner Aktif', BannerAdvertisement::where('is_active', 'active')->count())
                ->description('Sedang tayang')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('success'),
        ];
    }
}
