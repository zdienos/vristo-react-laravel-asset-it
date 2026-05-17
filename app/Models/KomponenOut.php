<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenOut extends Model
{
    use HasFactory;

    protected $table = 'tb_komponen_out';
    protected $primaryKey = 'id_komponen_out';

    protected $fillable = [
        'id_komponen',
        'id_asset',
        'jumlah',
        'keterangan',
    ];

    public function komponen()
    {
        return $this->belongsTo(Komponen::class, 'id_komponen', 'id_komponen');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'id_asset', 'id_asset');
    }
}
