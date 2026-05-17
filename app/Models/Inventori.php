<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventori extends Model
{
    use HasFactory;

    protected $table = 'tb_inventori';
    protected $primaryKey = 'id_inventori';

    protected $fillable = [
        'id_model',
        'stok',
    ];

    public function model()
    {
        return $this->belongsTo(ModelProduk::class, 'id_model', 'id_model');
    }

    public function stokMasuk()
    {
        return $this->hasMany(InventoriIn::class, 'id_inventori', 'id_inventori');
    }

    public function stokKeluar()
    {
        return $this->hasMany(InventoriOut::class, 'id_inventori', 'id_inventori');
    }
}
