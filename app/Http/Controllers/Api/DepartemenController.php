<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    public function index()
    {
        return Departemen::orderBy('nama_departemen')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_departemen' => 'required|string|max:255|unique:tb_departemen',
        ]);

        $departemen = Departemen::create($validated);

        return response()->json($departemen, 201);
    }

    public function show(Departemen $departemen)
    {
        return $departemen;
    }

    public function update(Request $request, Departemen $departemen)
    {
        $validated = $request->validate([
            'nama_departemen' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('tb_departemen')->ignore($departemen->id_departemen, 'id_departemen')],
        ]);

        $departemen->update($validated);

        return response()->json($departemen);
    }

    public function destroy(Departemen $departemen)
    {
        $departemen->delete();

        return response()->noContent();
    }
}
