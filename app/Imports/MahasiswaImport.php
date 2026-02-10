<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class MahasiswaImport implements ToModel, WithHeadingRow, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {

            // Ambil Prodi Dulu Cuk
            $prodi = Prodi::where('nama_prodi', 'LIKE', '%' . $row['prodi']. '%')->first();

            if (!$prodi) {
                throw new \Exception('Prodi ' . $row['prodi'] . ' tidak ditemukan');
            }

            // Buat User
            $user = User::updateOrCreate(
                [
                    'nim' => $row['nim']
                ],
                [
                    'name' => $row['nama'],
                    'email' => strtolower(str_replace(' ', '', $row['nama'])) . $row['nim'] . '@abulyatama.ac.id',
                    'password' => Hash::make('123'),
                    'role' => 'mahasiswa',
                ]
            );

            return Mahasiswa::updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'prodi_id' => $prodi->id,
                    'nama' => $row['nama'],
                    'jenis_kelamin' => strtoupper($row['jenis_kelamin']), 
                    'jumlah_sks'    => $row['jumlah_sks'],
                    'status'        => $row['status'] ?? 'aktif',
                ]
            );

        });
        
    }
}
