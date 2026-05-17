<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetFile extends Model
{
    use HasFactory;

    protected $table = 'tb_asset_file';
    protected $primaryKey = 'id_asset_file';

    protected $fillable = [
        'id_asset',
        'nama_file',
        'path',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'id_asset', 'id_asset');
    }
}
