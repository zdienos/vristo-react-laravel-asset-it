<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'tb_lokasi';
    protected $primaryKey = 'id_lokasi';

    protected $fillable = [
        'nama_lokasi',
    ];

    public function penggunas()
    {
        return $this->hasMany(Pengguna::class, 'id_lokasi', 'id_lokasi');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'id_lokasi', 'id_lokasi');
    }
}
