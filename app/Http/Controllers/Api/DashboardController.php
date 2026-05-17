<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Asesoris;
use App\Models\Inventori;
use App\Models\Komponen;
use App\Models\Kategori;
use App\Models\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Asset counts by status
        $totalAsset = Asset::count();
        $assetInUse = Asset::where('id_status', 1)->count();
        $assetReady = Asset::where('id_status', 2)->count();
        $assetRepair = Asset::where('id_status', 3)->count();
        $assetDamaged = Asset::where('id_status', 4)->count();
        $assetSold = Asset::where('id_status', 5)->count();

        // Stock totals
        $totalStokAsesoris = Asesoris::sum('stok');
        $totalDigunakanAsesoris = Asesoris::sum('digunakan');
        $totalStokInventori = Inventori::sum('stok');
        $totalStokKomponen = Komponen::sum('stok');
        $totalDigunakanKomponen = Komponen::sum('digunakan');

        // Asset by category
        $assetByCategory = Kategori::where('id_tipe', 1)
            ->withCount(['models' => function ($query) {
                $query->select(\DB::raw('count(distinct tb_asset.id_asset)'))
                    ->leftJoin('tb_asset', 'tb_asset.id_model', '=', 'tb_model.id_model');
            }])
            ->get()
            ->map(function ($kat) {
                return [
                    'kategori' => $kat->nama_kategori,
                    'jumlah' => $kat->models_count,
                ];
            });

        // Recent logs
        $recentLogs = Log::latest()->take(20)->get();

        return response()->json([
            'asset' => [
                'total' => $totalAsset,
                'in_use' => $assetInUse,
                'ready' => $assetReady,
                'repair' => $assetRepair,
                'damaged' => $assetDamaged,
                'sold' => $assetSold,
            ],
            'asesoris' => [
                'stok' => $totalStokAsesoris,
                'digunakan' => $totalDigunakanAsesoris,
            ],
            'inventori' => [
                'stok' => $totalStokInventori,
            ],
            'komponen' => [
                'stok' => $totalStokKomponen,
                'digunakan' => $totalDigunakanKomponen,
            ],
            'asset_by_category' => $assetByCategory,
            'recent_logs' => $recentLogs,
        ]);
    }
}
