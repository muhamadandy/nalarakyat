<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerAdvertisementResource\Pages;
use App\Models\BannerAdvertisement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerAdvertisementResource extends Resource
{
    protected static ?string $model = BannerAdvertisement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Iklan Banner';

    protected static ?string $modelLabel = 'Iklan Banner';

    protected static ?string $pluralModelLabel = 'Iklan Banner';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('link')
                    ->label('URL Tujuan')
                    ->url()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('https://example.com')
                    ->helperText(
                        'Alamat tujuan ketika banner diklik.'
                    ),

                Forms\Components\Select::make('type')
                    ->label('Posisi Banner')
                    ->options([
                        'header' => 'Header',
                        'sidebar' => 'Sidebar',
                        'article' => 'Dalam Artikel',
                        'footer' => 'Footer',
                    ])
                    ->required()
                    ->native(false)
                    ->helperText(
                        'Pilih posisi banner yang akan digunakan di website.'
                    ),

                Forms\Components\FileUpload::make('thumbnail')
                    ->label('Gambar Banner')
                    ->image()
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ])
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('banner-advertisements')
                    ->visibility('public')
                    ->imagePreviewHeight('200')
                    ->panelLayout('integrated')
                    ->required()
                    ->helperText(
                        'Format JPG, PNG, atau WebP. Maksimal 2 MB.'
                    ),

                Forms\Components\Select::make('is_active')
                    ->label('Status Iklan')
                    ->options([
                        'active' => 'Aktif',
                        'not_active' => 'Tidak Aktif',
                    ])
                    ->default('not_active')
                    ->required()
                    ->native(false),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Banner')
                    ->disk('public')
                    ->height(60)
                    ->width(120),

                Tables\Columns\TextColumn::make('type')
                    ->label('Posisi')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'header' => 'Header',
                            'sidebar' => 'Sidebar',
                            'article' => 'Dalam Artikel',
                            'footer' => 'Footer',
                            default => ucfirst($state),
                        }
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('link')
                    ->label('URL Tujuan')
                    ->limit(35)
                    ->searchable()
                    ->url(
                        fn (BannerAdvertisement $record): string =>
                            $record->link,
                        shouldOpenInNewTab: true
                    ),

                Tables\Columns\TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'active' => 'Aktif',
                            'not_active' => 'Tidak Aktif',
                            default => $state,
                        }
                    )
                    ->color(
                        fn (string $state): string => match ($state) {
                            'active' => 'success',
                            'not_active' => 'gray',
                            default => 'gray',
                        }
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'not_active' => 'Tidak Aktif',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Posisi Banner')
                    ->options([
                        'header' => 'Header',
                        'sidebar' => 'Sidebar',
                        'article' => 'Dalam Artikel',
                        'footer' => 'Footer',
                    ]),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBannerAdvertisements::route('/'),
            'create' => Pages\CreateBannerAdvertisement::route('/create'),
            'edit' => Pages\EditBannerAdvertisement::route('/{record}/edit'),
        ];
    }
}
