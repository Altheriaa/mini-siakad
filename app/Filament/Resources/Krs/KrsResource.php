<?php

namespace App\Filament\Resources\Krs;

use App\Filament\Resources\Krs\Pages\ManageKrs;
use App\Models\Krs;
use App\Models\TahunAkademik;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Forms\Get;

class KrsResource extends Resource
{
    protected static ?string $model = Krs::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static string | UnitEnum | null $navigationGroup = 'Akademik';

    public static function getPluralLabel(): string
    {
        return 'KRS';
    }

    public static function getLabel(): string
    {
        return 'KRS';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('mahasiswa_id')
                    ->relationship('mahasiswa', 'nama')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Mahasiswa')
                    ->live(),

                Select::make('mata_kuliah_id')
                    ->relationship('mataKuliah', 'nama_mk')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Mata Kuliah')
                    ->rule(function ($get, $record) {

                        $mahasiswaId = $get('mahasiswa_id');
                        $tahunId = $get('tahun_akademik_id');

                        return Rule::unique('krs', 'mata_kuliah_id')
                            ->where(function ($query) use ($mahasiswaId, $tahunId) {
                                return $query
                                    ->where('mahasiswa_id', $mahasiswaId)
                                    ->where('tahun_akademik_id', $tahunId);
                            })
                            ->ignore($record?->id);
                    })
                    ->validationMessages([
                        'unique' => 'Mahasiswa ini sudah mengambil Mata Kuliah tersebut di semester ini!',
                    ]),

                Select::make('tahun_akademik_id')
                    ->label('Tahun Akademik')
                    ->options(TahunAkademik::all()->mapWithKeys(function ($item) {
                        return [$item->id => $item->tahun . ' - ' . ucfirst($item->semester)];
                    }))
                    ->default(fn() => TahunAkademik::where('aktif', 1)->first()?->id)
                    ->required()
                    ->disabled(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('mataKuliah.nama_mk')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('tahunAkademik.tahun')
                    ->label('Tahun Akademik')
                    ->searchable(),
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
            'index' => ManageKrs::route('/'),
        ];
    }
}
