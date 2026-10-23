<?php

namespace App\Filament\Widgets;

use App\Models\ArticleNews;
use Filament\Widgets\ChartWidget;

class ArticlesChart extends ChartWidget
{
    protected static ?string $heading = 'Artikel Dibuat per Bulan';

    protected static ?string $description = '6 bulan terakhir';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);

            $labels[] = $month->translatedFormat('M Y');
            $data[] = ArticleNews::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Artikel',
                    'data' => $data,
                    'borderColor' => '#b91c1c',
                    'backgroundColor' => 'rgba(185, 28, 28, 0.12)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]],
            ],
        ];
    }
}
