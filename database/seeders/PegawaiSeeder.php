<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pegawai::create([
            'nip' => '197204102011002',
            'nama_pegawai' => 'Handayaningrum Chori Ulzana',
            'nama_bidang' => 'Inspektorat',
            'nama_jabatan' => 'Inspektur',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '197506232019057',
            'nama_pegawai' => 'Rieke Puspita Sari',
            'nama_bidang' => 'Sekretariat',
            'nama_jabatan' => 'Sekretaris Dinas',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '198209152012728',
            'nama_pegawai' => 'Darwito',
            'nama_bidang' => 'Sekretariat',
            'nama_jabatan' => 'Sekretaris Dinas',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '198909172022034',
            'nama_pegawai' => 'Arfian Dwiki Rosyadi',
            'nama_bidang' => 'Inspektur Pembantu Wilayah I',
            'nama_jabatan' => 'Inspektur Pembantu',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '198812092011392',
            'nama_pegawai' => 'Mahalini',
            'nama_bidang' => 'Inspektur Pembantu Wilayah III',
            'nama_jabatan' => 'Inspektur Pembantu',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '198109092020194',
            'nama_pegawai' => 'Aura Fitri',
            'nama_bidang' => 'Inspektur Pembantu Wilayah II',
            'nama_jabatan' => 'Inspektur Pembantu',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '198511292012943',
            'nama_pegawai' => 'Dhea Anggun',
            'nama_bidang' => 'Inspektur Pembantu Wilayah IV',
            'nama_jabatan' => 'Inspektur Pembantu',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '198309112015099',
            'nama_pegawai' => 'Altruis Aldebaran',
            'nama_bidang' => 'Inspektur Pembantu Wilayah III',
            'nama_jabatan' => 'Auditor Ahli Muda',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '198402152018182',
            'nama_pegawai' => 'Anita Wahyu Pratama Putri',
            'nama_bidang' => 'Inspektur Pembantu Wilayah IV',
            'nama_jabatan' => 'Auditor Ahli Madya',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '198002182013478',
            'nama_pegawai' => 'Tiara Andini',
            'nama_bidang' => 'Inspektur Pembantu Wilayah II',
            'nama_jabatan' => 'Auditor Ahli Pertama',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '199912042019392',
            'nama_pegawai' => 'Cindy Wahyu',
            'nama_bidang' => 'Inspektur Pembantu Wilayah III',
            'nama_jabatan' => 'Auditor Ahli Madya',
            'password' => bcrypt('12345678'),
        ]);
        Pegawai::create([
            'nip' => '199305022015283',
            'nama_pegawai' => 'Rafi Yusron',
            'nama_bidang' => 'Inspektur Pembantu Wilayah IV',
            'nama_jabatan' => 'Auditor Ahli Pertama',
            'password' => bcrypt('12345678'),
        ]);
    }
}
