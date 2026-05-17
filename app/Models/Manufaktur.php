<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufaktur extends Model
{
    use HasFactory;

    protected $table = 'tb_manufaktur';
    protected $primaryKey = 'id_manufaktur';

    protected $fillable = [
        'nama_manufaktur',
    ];

    public function models()
    {
        return $this->hasMany(ModelProduk::class, 'id_manufaktur', 'id_manufaktur');
    }
}
