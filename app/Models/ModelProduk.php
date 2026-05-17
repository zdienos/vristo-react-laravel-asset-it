<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelProduk extends Model
{
    use HasFactory;

    protected $table = 'tb_model';
    protected $primaryKey = 'id_model';

    protected $fillable = [
        'nama_model',
        'id_kategori',
        'id_manufaktur',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function manufaktur()
    {
        return $this->belongsTo(Manufaktur::class, 'id_manufaktur', 'id_manufaktur');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'id_model', 'id_model');
    }

    public function asesoris()
    {
        return $this->hasOne(Asesoris::class, 'id_model', 'id_model');
    }

    public function inventori()
    {
        return $this->hasOne(Inventori::class, 'id_model', 'id_model');
    }

    public function komponen()
    {
        return $this->hasOne(Komponen::class, 'id_model', 'id_model');
    }
}
