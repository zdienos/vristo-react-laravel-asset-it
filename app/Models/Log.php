<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'tb_log';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'log_user',
        'log_tipe',
        'log_aksi',
        'log_item',
        'log_assign_to',
        'log_assign_type',
    ];
}
