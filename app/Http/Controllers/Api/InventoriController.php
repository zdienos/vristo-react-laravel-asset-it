<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventori;
use App\Models\InventoriIn;
use App\Models\InventoriOut;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoriController extends Controller
{
    public function index()
    {
        return Inventori::with(['model.manufaktur', 'model.kategori'])->latest()->get();
    }

    public function show(Inventori $inventori)
    {
        return $inventori->load(['model.manufaktur', 'model.kategori', 'stokMasuk', 'stokKeluar.pengguna']);
    }

    public function stokMasuk(Request $request)
    {
        $validated = $request->validate([
            'id_inventori' => 'required|exists:tb_inventori,id_inventori',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $inventori = Inventori::findOrFail($validated['id_inventori']);
        $inventori->increment('stok', $validated['jumlah']);

        InventoriIn::create([
            'id_inventori' => $inventori->id_inventori,
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? 'Stok masuk',
        ]);

        $this->activityLog('inventori', 'menambah stok', $inventori->id_inventori, null, null);

        return response()->json($inventori->load('model'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'id_inventori' => 'required|exists:tb_inventori,id_inventori',
            'id_pengguna' => 'required|exists:tb_pengguna,id_pengguna',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $inventori = Inventori::findOrFail($validated['id_inventori']);

        if ($inventori->stok < $validated['jumlah']) {
            return response()->json(['message' => 'Stok tidak cukup'], 422);
        }

        $inventori->decrement('stok', $validated['jumlah']);

        InventoriOut::create([
            'id_inventori' => $inventori->id_inventori,
            'id_pengguna' => $validated['id_pengguna'],
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? 'Checkout',
        ]);

        $this->activityLog('inventori', 'mengeluarkan stok', $inventori->id_inventori, $validated['id_pengguna'], 'pengguna');

        return response()->json($inventori->load('model'));
    }

    public function stokMasukHistory()
    {
        return InventoriIn::with('inventori.model')->latest()->get();
    }

    public function stokKeluarHistory()
    {
        return InventoriOut::with(['inventori.model', 'pengguna'])->latest()->get();
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
