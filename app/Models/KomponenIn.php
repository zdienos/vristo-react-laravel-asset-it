<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenIn extends Model
{
    use HasFactory;

    protected $table = 'tb_komponen_in';
    protected $primaryKey = 'id_komponen_in';

    protected $fillable = [
        'id_komponen',
        'jumlah',
        'keterangan',
    ];

    public function komponen()
    {
        return $this->belongsTo(Komponen::class, 'id_komponen', 'id_komponen');
    }
}
