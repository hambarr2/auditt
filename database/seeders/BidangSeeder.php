<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bidang::create([
            'nama_bidang' => 'Inspektorat'
        ]);
        Bidang::create([
            'nama_bidang' => 'Sekretariat'
        ]);
        Bidang::create([
            'nama_bidang' => 'Inspektur Pembantu Wilayah I'
        ]);
        Bidang::create([
            'nama_bidang' => 'Inspektur Pembantu Wilayah II'
        ]);
        Bidang::create([
            'nama_bidang' => 'Inspektur Pembantu Wilayah III'
        ]);
        Bidang::create([
            'nama_bidang' => 'Inspektur Pembantu Wilayah IV'
        ]);
    }
}
