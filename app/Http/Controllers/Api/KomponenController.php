<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Komponen;
use App\Models\KomponenIn;
use App\Models\KomponenOut;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomponenController extends Controller
{
    public function index()
    {
        return Komponen::with(['model.manufaktur', 'model.kategori'])->latest()->get();
    }

    public function show(Komponen $komponen)
    {
        return $komponen->load(['model.manufaktur', 'model.kategori', 'stokMasuk', 'stokKeluar.asset']);
    }

    public function stokMasuk(Request $request)
    {
        $validated = $request->validate([
            'id_komponen' => 'required|exists:tb_komponen,id_komponen',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $komponen = Komponen::findOrFail($validated['id_komponen']);
        $komponen->increment('stok', $validated['jumlah']);

        KomponenIn::create([
            'id_komponen' => $komponen->id_komponen,
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? 'Stok masuk',
        ]);

        $this->activityLog('komponen', 'menambah stok', $komponen->id_komponen, null, null);

        return response()->json($komponen->load('model'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'id_komponen' => 'required|exists:tb_komponen,id_komponen',
            'id_asset' => 'required|exists:tb_asset,id_asset',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $komponen = Komponen::findOrFail($validated['id_komponen']);

        if ($komponen->stok < $validated['jumlah']) {
            return response()->json(['message' => 'Stok tidak cukup'], 422);
        }

        $komponen->decrement('stok', $validated['jumlah']);
        $komponen->increment('digunakan', $validated['jumlah']);

        KomponenOut::create([
            'id_komponen' => $komponen->id_komponen,
            'id_asset' => $validated['id_asset'],
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? 'Checkout',
        ]);

        $this->activityLog('komponen', 'mengeluarkan stok', $komponen->id_komponen, $validated['id_asset'], 'asset');

        return response()->json($komponen->load('model'));
    }

    public function checkin(Request $request)
    {
        $validated = $request->validate([
            'id_komponen' => 'required|exists:tb_komponen,id_komponen',
            'jumlah' => 'required|integer|min:1',
        ]);

        $komponen = Komponen::findOrFail($validated['id_komponen']);

        if ($komponen->digunakan < $validated['jumlah']) {
            return response()->json(['message' => 'Jumlah digunakan tidak cukup'], 422);
        }

        $komponen->decrement('digunakan', $validated['jumlah']);
        $komponen->increment('stok', $validated['jumlah']);

        $this->activityLog('komponen', 'checkin', $komponen->id_komponen, null, null);

        return response()->json($komponen->load('model'));
    }

    public function stokMasukHistory()
    {
        return KomponenIn::with('komponen.model')->latest()->get();
    }

    public function stokKeluarHistory()
    {
        return KomponenOut::with(['komponen.model', 'asset'])->latest()->get();
    }

    private function activityLog($tipe, $aksi, $item, $assignTo, $assignType)
    {
        Log::create([
            'log_user' => Auth::user()->name ?? 'system',
            'log_tipe' => $tipe,
            'log_aksi' => $aksi,
            'log_item' => $item,
            'log_assign_to' => $assignTo,
            'log_assign_type' => $assignType,
        ]);
    }
}
