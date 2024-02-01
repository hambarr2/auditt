<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jabatan::create([
            'nama_jabatan' => 'Inspektur',
            'nama_bidang' => 'Inspektorat',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Sekretaris Dinas',
            'nama_bidang' => 'Sekretariat',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Inspektur Pembantu',
            'nama_bidang' => 'Inspektur Pembantu Wilayah I',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Pertama',
            'nama_bidang' => 'Inspektur Pembantu Wilayah I',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Muda',
            'nama_bidang' => 'Inspektur Pembantu Wilayah I',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Madya',
            'nama_bidang' => 'Inspektur Pembantu Wilayah I',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Inspektur Pembantu',
            'nama_bidang' => 'Inspektur Pembantu Wilayah II',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Pertama',
            'nama_bidang' => 'Inspektur Pembantu Wilayah II',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Muda',
            'nama_bidang' => 'Inspektur Pembantu Wilayah II',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Madya',
            'nama_bidang' => 'Inspektur Pembantu Wilayah II',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Inspektur Pembantu',
            'nama_bidang' => 'Inspektur Pembantu Wilayah III',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Pertama',
            'nama_bidang' => 'Inspektur Pembantu Wilayah III',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Muda',
            'nama_bidang' => 'Inspektur Pembantu Wilayah III',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Madya',
            'nama_bidang' => 'Inspektur Pembantu Wilayah III',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Inspektur Pembantu',
            'nama_bidang' => 'Inspektur Pembantu Wilayah IV',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Pertama',
            'nama_bidang' => 'Inspektur Pembantu Wilayah IV',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Muda',
            'nama_bidang' => 'Inspektur Pembantu Wilayah IV',
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Auditor Ahli Madya',
            'nama_bidang' => 'Inspektur Pembantu Wilayah IV',
        ]);
    }
}
