<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        return Lokasi::orderBy('nama_lokasi')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255|unique:tb_lokasi',
        ]);

        $lokasi = Lokasi::create($validated);

        return response()->json($lokasi, 201);
    }

    public function show(Lokasi $lokasi)
    {
        return $lokasi;
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $validated = $request->validate([
            'nama_lokasi' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('tb_lokasi')->ignore($lokasi->id_lokasi, 'id_lokasi')],
        ]);

        $lokasi->update($validated);

        return response()->json($lokasi);
    }

    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();

        return response()->noContent();
    }
}
