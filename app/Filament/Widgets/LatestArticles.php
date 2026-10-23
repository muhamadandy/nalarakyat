<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ArticleNewsResource;
use App\Models\ArticleNews;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestArticles extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Artikel Terbaru')
            ->query(
                ArticleNews::query()->with(['category', 'author'])->latest()->limit(5)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(48)
                    ->width(80),

                Tables\Columns\TextColumn::make('name')
                    ->label('Judul')
                    ->limit(50),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y, H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Ubah')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (ArticleNews $record): string => ArticleNewsResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
