<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoriIn extends Model
{
    use HasFactory;

    protected $table = 'tb_inventori_in';
    protected $primaryKey = 'id_inventori_in';

    protected $fillable = [
        'id_inventori',
        'jumlah',
        'keterangan',
    ];

    public function inventori()
    {
        return $this->belongsTo(Inventori::class, 'id_inventori', 'id_inventori');
    }
}
