<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLog extends Model
{
    use HasFactory;

    protected $table = 'tb_asset_log';
    protected $primaryKey = 'id_asset_log';

    protected $fillable = [
        'id_asset',
        'id_status',
        'assign_to',
        'assign_type',
        'keterangan',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'id_asset', 'id_asset');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }
}
