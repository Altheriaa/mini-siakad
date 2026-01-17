<?php

namespace App\Filament\Resources\JadwalKkns;

use App\Filament\Resources\JadwalKkns\Pages\ManageJadwalKkns;
use App\Models\JadwalKkn;
use BackedEnum;
use Carbon\Unit;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;

class JadwalKknResource extends Resource
{
    protected static ?string $model = JadwalKkn::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static string | UnitEnum | null $navigationGroup = 'Akademik';

    public static function getPluralLabel(): string
    {
        return 'Jadwal KKN';
    }

    public static function getLabel(): string
    {
        return 'Jadwal KKN';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tahun_akademik_id')
                    ->relationship('tahunAkademik', 'tahun')
                    ->label('Tahun Akademik')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->unique(),
                DatePicker::make('tanggal_dibuka')
                    ->label('Tanggal Dibuka'),
                DatePicker::make('tanggal_ditutup')
                    ->label('Tanggal Ditutup'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahunAkademik.tahun')
                    ->sortable(),
                TextColumn::make('tanggal_dibuka')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_ditutup')
                    ->date()
                    ->sortable(),
                IconColumn::make('status_pendaftaran')
                    ->label('Dibuka/Ditutup')
                    ->boolean(),
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
            'index' => ManageJadwalKkns::route('/'),
        ];
    }
}
