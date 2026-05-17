<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoriOut extends Model
{
    use HasFactory;

    protected $table = 'tb_inventori_out';
    protected $primaryKey = 'id_inventori_out';

    protected $fillable = [
        'id_inventori',
        'id_pengguna',
        'jumlah',
        'keterangan',
    ];

    public function inventori()
    {
        return $this->belongsTo(Inventori::class, 'id_inventori', 'id_inventori');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
