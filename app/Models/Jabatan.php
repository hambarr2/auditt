<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'm_jabatan';

    protected $primaryKey = 'id_jabatan';

    protected $fillable = [
        'nama_jabatan',
        'nama_bidang',
    ];

    public $timestamps = false;
}
