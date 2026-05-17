<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asesoris;
use App\Models\AsesorisIn;
use App\Models\AsesorisOut;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsesorisController extends Controller
{
    public function index()
    {
        return Asesoris::with(['model.manufaktur', 'model.kategori'])->latest()->get();
    }

    public function show(Asesoris $asesoris)
    {
        return $asesoris->load(['model.manufaktur', 'model.kategori', 'stokMasuk', 'stokKeluar.pengguna']);
    }

    public function stokMasuk(Request $request)
    {
        $validated = $request->validate([
            'id_asesoris' => 'required|exists:tb_asesoris,id_asesoris',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $asesoris = Asesoris::findOrFail($validated['id_asesoris']);
        $asesoris->increment('stok', $validated['jumlah']);

        AsesorisIn::create([
            'id_asesoris' => $asesoris->id_asesoris,
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? 'Stok masuk',
        ]);

        $this->activityLog('asesoris', 'menambah stok', $asesoris->id_asesoris, null, null);

        return response()->json($asesoris->load('model'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'id_asesoris' => 'required|exists:tb_asesoris,id_asesoris',
            'id_pengguna' => 'required|exists:tb_pengguna,id_pengguna',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $asesoris = Asesoris::findOrFail($validated['id_asesoris']);

        if ($asesoris->stok < $validated['jumlah']) {
            return response()->json(['message' => 'Stok tidak cukup'], 422);
        }

        $asesoris->decrement('stok', $validated['jumlah']);
        $asesoris->increment('digunakan', $validated['jumlah']);

        AsesorisOut::create([
            'id_asesoris' => $asesoris->id_asesoris,
            'id_pengguna' => $validated['id_pengguna'],
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? 'Checkout',
        ]);

        $this->activityLog('asesoris', 'mengeluarkan stok', $asesoris->id_asesoris, $validated['id_pengguna'], 'pengguna');

        return response()->json($asesoris->load('model'));
    }

    public function checkin(Request $request)
    {
        $validated = $request->validate([
            'id_asesoris' => 'required|exists:tb_asesoris,id_asesoris',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|in:ready,rusak',
        ]);

        $asesoris = Asesoris::findOrFail($validated['id_asesoris']);

        if ($asesoris->digunakan < $validated['jumlah']) {
            return response()->json(['message' => 'Jumlah digunakan tidak cukup'], 422);
        }

        $asesoris->decrement('digunakan', $validated['jumlah']);

        if ($validated['status'] === 'ready') {
            $asesoris->increment('stok', $validated['jumlah']);
        } else {
            $asesoris->increment('rusak', $validated['jumlah']);
        }

        $this->activityLog('asesoris', 'checkin', $asesoris->id_asesoris, null, null);

        return response()->json($asesoris->load('model'));
    }

    public function stokMasukHistory()
    {
        return AsesorisIn::with('asesoris.model')->latest()->get();
    }

    public function stokKeluarHistory()
    {
        return AsesorisOut::with(['asesoris.model', 'pengguna'])->latest()->get();
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
