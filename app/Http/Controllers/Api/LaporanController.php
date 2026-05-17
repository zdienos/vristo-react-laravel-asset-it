<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Asesoris;
use App\Models\Inventori;
use App\Models\Komponen;
use App\Models\Kategori;
use App\Models\Tipe;
use App\Models\Log;

class LaporanController extends Controller
{
    public function aktivitas()
    {
        $logs = Log::latest()->paginate(50);

        $totalAsset = Asset::count();
        $assetInUse = Asset::where('id_status', 1)->count();
        $assetReady = Asset::where('id_status', 2)->count();
        $assetRepair = Asset::where('id_status', 3)->count();
        $assetDamaged = Asset::where('id_status', 4)->count();

        $totalStokAsesoris = Asesoris::sum('stok');
        $totalDigunakanAsesoris = Asesoris::sum('digunakan');
        $totalStokInventori = Inventori::sum('stok');
        $totalStokKomponen = Komponen::sum('stok');
        $totalDigunakanKomponen = Komponen::sum('digunakan');

        $kategoriByTipe = Tipe::with('kategoris')->get();

        return response()->json([
            'logs' => $logs,
            'statistik' => [
                'asset' => [
                    'total' => $totalAsset,
                    'in_use' => $assetInUse,
                    'ready' => $assetReady,
                    'repair' => $assetRepair,
                    'damaged' => $assetDamaged,
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
            ],
            'kategori_by_tipe' => $kategoriByTipe,
        ]);
    }
}
