<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PKA extends Model
{
    use HasFactory;

    protected $table = 'pka';

    protected $primaryKey = 'id_pka';

    protected $fillable = [
        'id_spt',
        'tujuan', 
        'langkah_kerja', 
        'pelaksana',
        'waktu',
        'no_kka',
        'catatan',
    ];

    public $timestamps = false;

    public function relasi_id_spt()
    {
        return $this->belongsTo(SPT::class,'id_spt');
    }
}