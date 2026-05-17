<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsesorisIn extends Model
{
    use HasFactory;

    protected $table = 'tb_asesoris_in';
    protected $primaryKey = 'id_asesoris_in';

    protected $fillable = [
        'id_asesoris',
        'jumlah',
        'keterangan',
    ];

    public function asesoris()
    {
        return $this->belongsTo(Asesoris::class, 'id_asesoris', 'id_asesoris');
    }
}
