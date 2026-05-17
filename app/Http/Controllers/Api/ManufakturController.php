<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manufaktur;
use Illuminate\Http\Request;

class ManufakturController extends Controller
{
    public function index()
    {
        return Manufaktur::orderBy('nama_manufaktur')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_manufaktur' => 'required|string|max:255|unique:tb_manufaktur',
        ]);

        $manufaktur = Manufaktur::create($validated);

        return response()->json($manufaktur, 201);
    }

    public function show(Manufaktur $manufaktur)
    {
        return $manufaktur;
    }

    public function update(Request $request, Manufaktur $manufaktur)
    {
        $validated = $request->validate([
            'nama_manufaktur' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('tb_manufaktur')->ignore($manufaktur->id_manufaktur, 'id_manufaktur')],
        ]);

        $manufaktur->update($validated);

        return response()->json($manufaktur);
    }

    public function destroy(Manufaktur $manufaktur)
    {
        $manufaktur->delete();

        return response()->noContent();
    }
}
