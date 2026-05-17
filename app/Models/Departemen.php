<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'tb_departemen';
    protected $primaryKey = 'id_departemen';

    protected $fillable = [
        'nama_departemen',
    ];

    public function penggunas()
    {
        return $this->hasMany(Pengguna::class, 'id_departemen', 'id_departemen');
    }
}
