<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Pegawai extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'm_pegawai';

    protected $primaryKey = 'nip';

    protected $fillable = [
        'nip',
        'nama_pegawai',        
        'nama_jabatan',
        'nama_bidang',
        'password',
        'password_reset',
    ];

    protected $hidden = 'password';

    public $timestamps = false;
}
