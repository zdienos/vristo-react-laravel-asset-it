<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesoris extends Model
{
    use HasFactory;

    protected $table = 'tb_asesoris';
    protected $primaryKey = 'id_asesoris';

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
        return $this->hasMany(AsesorisIn::class, 'id_asesoris', 'id_asesoris');
    }

    public function stokKeluar()
    {
        return $this->hasMany(AsesorisOut::class, 'id_asesoris', 'id_asesoris');
    }
}
