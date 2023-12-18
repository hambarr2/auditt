<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPT extends Model
{
    use HasFactory;

    protected $table = 't_spt';

    protected $primaryKey = 'id_spt';

    protected $fillable = [
        'nomor_spt',
        'jenis_spt',
        'dasar_spt',
        'jangka_waktu',
        'status_spt',
        'untuk_spt',
        'kurun_waktu_awal',
        'kurun_waktu_akhir',
        'obyek_audit',
    ];

    public function anggotaSPT()
    {
        return $this->hasMany(AnggotaSPT::class, 'id_spt');
    }
}