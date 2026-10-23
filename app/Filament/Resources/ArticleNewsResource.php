<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleNewsResource\Pages;
use App\Models\ArticleNews;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ArticleNewsResource extends Resource
{
    protected static ?string $model = ArticleNews::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Artikel Berita';

    protected static ?string $modelLabel = 'Artikel Berita';

    protected static ?string $pluralModelLabel = 'Artikel Berita';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn (Set $set, ?string $state) => $set('slug', Str::slug($state))
                    ),

                Forms\Components\Hidden::make('slug'),

                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('author_id')
                    ->label('Penulis')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\FileUpload::make('thumbnail')
                    ->label('Gambar Utama')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('article-thumbnails')
                    ->visibility('public')
                    ->imagePreviewHeight('200')
                    ->required()
                    ->helperText('Format JPG, PNG, atau WebP. Maksimal 2 MB.'),

                Forms\Components\Select::make('is_featured')
                    ->label('Artikel Unggulan')
                    ->options([
                        'featured' => 'Unggulan',
                        'not_featured' => 'Biasa',
                    ])
                    ->default('not_featured')
                    ->required()
                    ->native(false),

                Forms\Components\RichEditor::make('content')
                    ->label('Isi Artikel')
                    ->required()
                    ->columnSpanFull()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('article-attachments')
                    ->fileAttachmentsVisibility('public'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(60)
                    ->width(100),

                Tables\Columns\TextColumn::make('name')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis')
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_featured')
                    ->label('Unggulan')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'featured' => 'Unggulan',
                            'not_featured' => 'Biasa',
                            default => $state,
                        }
                    )
                    ->color(
                        fn (string $state): string => match ($state) {
                            'featured' => 'success',
                            default => 'gray',
                        }
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('is_featured')
                    ->label('Unggulan')
                    ->options([
                        'featured' => 'Unggulan',
                        'not_featured' => 'Biasa',
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
            'index' => Pages\ListArticleNews::route('/'),
            'create' => Pages\CreateArticleNews::route('/create'),
            'edit' => Pages\EditArticleNews::route('/{record}/edit'),
        ];
    }
}
