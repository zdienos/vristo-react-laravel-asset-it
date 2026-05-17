<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'tb_asset';
    protected $primaryKey = 'id_asset';

    protected $fillable = [
        'asset_tag',
        'nama_asset',
        'no_seri',
        'id_model',
        'id_status',
        'id_pengguna',
        'id_lokasi',
        'assign_to',
        'assign_type',
        'keterangan',
    ];

    public function model()
    {
        return $this->belongsTo(ModelProduk::class, 'id_model', 'id_model');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function logs()
    {
        return $this->hasMany(AssetLog::class, 'id_asset', 'id_asset');
    }

    public function files()
    {
        return $this->hasMany(AssetFile::class, 'id_asset', 'id_asset');
    }

    public function komponens()
    {
        return $this->hasMany(KomponenOut::class, 'id_asset', 'id_asset');
    }
}
