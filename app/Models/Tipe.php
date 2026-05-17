<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipe extends Model
{
    use HasFactory;

    protected $table = 'tb_tipe';
    protected $primaryKey = 'id_tipe';

    protected $fillable = [
        'nama_tipe',
    ];

    public function kategoris()
    {
        return $this->hasMany(Kategori::class, 'id_tipe', 'id_tipe');
    }
}
