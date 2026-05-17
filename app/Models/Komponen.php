<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    use HasFactory;

    protected $table = 'tb_komponen';
    protected $primaryKey = 'id_komponen';

    protected $fillable = [
        'id_model',
        'stok',
        'digunakan',
        'rusak',
    ];

    public function model()
    {
        return $this->belongsTo(ModelProduk::class, 'id_model', 'id_model');
    }

    public function stokMasuk()
    {
        return $this->hasMany(KomponenIn::class, 'id_komponen', 'id_komponen');
    }

    public function stokKeluar()
    {
        return $this->hasMany(KomponenOut::class, 'id_komponen', 'id_komponen');
    }
}
