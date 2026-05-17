<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'tb_pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nik',
        'nama_pengguna',
        'id_departemen',
        'id_lokasi',
        'telepon',
        'alamat',
        'keterangan',
    ];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'id_departemen', 'id_departemen');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'id_pengguna', 'id_pengguna');
    }
}
