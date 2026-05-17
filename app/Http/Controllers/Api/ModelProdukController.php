<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelProduk;
use App\Models\Asesoris;
use App\Models\Inventori;
use App\Models\Komponen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModelProdukController extends Controller
{
    public function index()
    {
        return ModelProduk::with(['kategori.tipe', 'manufaktur'])->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_model' => 'required|string|max:255',
            'id_kategori' => 'required|exists:tb_kategori,id_kategori',
            'id_manufaktur' => 'required|exists:tb_manufaktur,id_manufaktur',
        ]);

        return DB::transaction(function () use ($validated) {
            $model = ModelProduk::create($validated);

            // Auto-create stock record based on category type
            $kategori = $model->kategori;
            if ($kategori) {
                $tipe = $kategori->id_tipe;
                if ($tipe == 2) {
                    Asesoris::create(['id_model' => $model->id_model, 'stok' => 0, 'digunakan' => 0, 'rusak' => 0]);
                } elseif ($tipe == 3) {
                    Inventori::create(['id_model' => $model->id_model, 'stok' => 0]);
                } elseif ($tipe == 4) {
                    Komponen::create(['id_model' => $model->id_model, 'stok' => 0, 'digunakan' => 0, 'rusak' => 0]);
                }
            }

            return response()->json($model->load(['kategori.tipe', 'manufaktur']), 201);
        });
    }

    public function show(ModelProduk $modelProduk)
    {
        return $modelProduk->load(['kategori.tipe', 'manufaktur']);
    }

    public function update(Request $request, ModelProduk $modelProduk)
    {
        $validated = $request->validate([
            'nama_model' => 'required|string|max:255',
            'id_kategori' => 'required|exists:tb_kategori,id_kategori',
            'id_manufaktur' => 'required|exists:tb_manufaktur,id_manufaktur',
        ]);

        $modelProduk->update($validated);

        return response()->json($modelProduk->load(['kategori.tipe', 'manufaktur']));
    }

    public function destroy(ModelProduk $modelProduk)
    {
        $modelProduk->delete();

        return response()->noContent();
    }
}
