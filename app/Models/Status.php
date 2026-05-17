<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'tb_status';
    protected $primaryKey = 'id_status';

    protected $fillable = [
        'nama_status',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'id_status', 'id_status');
    }
}
