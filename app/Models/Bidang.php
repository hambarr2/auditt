<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'm_bidang';

    protected $primaryKey = 'id_bidang';

    protected $fillable = [
        'nama_bidang',
    ];

    public $timestamps = false;
}