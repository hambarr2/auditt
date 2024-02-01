<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaSPT extends Model
{
    use HasFactory;

    protected $table = 't_anggota_spt';

    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'id_spt',
        'nip',
        'keterangan',
        'tanggal_awal',
        'tanggal_akhir'
    ];

    public function relasi_pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nip');
    }

    public $timestamps = false;
}
