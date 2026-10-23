<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Penulis';

    protected static ?string $modelLabel = 'Penulis';

    protected static ?string $pluralModelLabel = 'Penulis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Penulis')
                    ->required()
                    ->maxLength(255),

                TextInput::make('occupation')
                    ->label('Pekerjaan / Jabatan')
                    ->maxLength(255)
                    ->placeholder('Contoh: Jurnalis'),

                FileUpload::make('avatar')
                    ->label('Avatar Penulis')
                    ->image()
                    ->acceptedFileTypes(['image/png'])
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('authors')
                    ->visibility('public')
                    ->imagePreviewHeight('200')
                    ->panelLayout('integrated')
                    ->helperText(
                        'Unggah avatar penulis berformat PNG, maksimal 2 MB.'
                    ),

                Forms\Components\Hidden::make('slug'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Penulis')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('occupation')
                    ->label('Pekerjaan / Jabatan')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
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
            'index' => Pages\ListAuthors::route('/'),

            'create' => Pages\CreateAuthor::route('/create'),

            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}