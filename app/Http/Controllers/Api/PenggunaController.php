<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index()
    {
        return Pengguna::with(['departemen', 'lokasi'])->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:50|unique:tb_pengguna',
            'nama_pengguna' => 'required|string|max:255',
            'id_departemen' => 'required|exists:tb_departemen,id_departemen',
            'id_lokasi' => 'required|exists:tb_lokasi,id_lokasi',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $pengguna = Pengguna::create($validated);

        return response()->json($pengguna->load(['departemen', 'lokasi']), 201);
    }

    public function show(Pengguna $pengguna)
    {
        return $pengguna->load(['departemen', 'lokasi']);
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'max:50', \Illuminate\Validation\Rule::unique('tb_pengguna')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'nama_pengguna' => 'required|string|max:255',
            'id_departemen' => 'required|exists:tb_departemen,id_departemen',
            'id_lokasi' => 'required|exists:tb_lokasi,id_lokasi',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $pengguna->update($validated);

        return response()->json($pengguna->load(['departemen', 'lokasi']));
    }

    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();

        return response()->noContent();
    }

    public function detail(Pengguna $pengguna)
    {
        $pengguna->load(['departemen', 'lokasi', 'assets.model', 'assets.status']);

        return response()->json($pengguna);
    }
}
