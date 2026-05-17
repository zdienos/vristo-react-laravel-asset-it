<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsesorisOut extends Model
{
    use HasFactory;

    protected $table = 'tb_asesoris_out';
    protected $primaryKey = 'id_asesoris_out';

    protected $fillable = [
        'id_asesoris',
        'id_pengguna',
        'jumlah',
        'keterangan',
    ];

    public function asesoris()
    {
        return $this->belongsTo(Asesoris::class, 'id_asesoris', 'id_asesoris');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
