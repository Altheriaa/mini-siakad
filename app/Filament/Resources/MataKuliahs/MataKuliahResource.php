<?php

namespace App\Filament\Resources\MataKuliahs;

use App\Filament\Resources\MataKuliahs\Pages\ManageMataKuliahs;
use App\Models\MataKuliah;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MataKuliahResource extends Resource
{
    protected static ?string $model = MataKuliah::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static string | UnitEnum | null $navigationGroup = 'Akademik';

    protected static ?string $recordTitleAttribute = 'kode_mk';

    public static function getPluralLabel(): string
    {
        return 'Mata Kuliah';
    }

    public static function getLabel(): string
    {
        return 'Mata Kuliah';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_mk')
                    ->label('Kode Mata Kuliah')
                    ->unique()
                    ->required(),
                TextInput::make('nama_mk')
                    ->label('Nama Mata Kuliah')
                    ->unique()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kode_mk')
            ->columns([
                TextColumn::make('kode_mk')
                    ->label('Kode Mata Kuliah')
                    ->searchable(),
                TextColumn::make('nama_mk')
                    ->label('Nama Mata Kuliah')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMataKuliahs::route('/'),
        ];
    }
}
