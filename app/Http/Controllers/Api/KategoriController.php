<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Tipe;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return Kategori::with('tipe')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_tipe' => 'required|exists:tb_tipe,id_tipe',
        ]);

        $kategori = Kategori::create($validated);

        return response()->json($kategori->load('tipe'), 201);
    }

    public function show(Kategori $kategori)
    {
        return $kategori->load('tipe');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_tipe' => 'required|exists:tb_tipe,id_tipe',
        ]);

        $kategori->update($validated);

        return response()->json($kategori->load('tipe'));
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return response()->noContent();
    }

    public function tipes()
    {
        return Tipe::all();
    }
}
