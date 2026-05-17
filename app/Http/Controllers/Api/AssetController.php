<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetLog;
use App\Models\AssetFile;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function index()
    {
        return Asset::with(['model.manufaktur', 'model.kategori', 'status', 'pengguna', 'lokasi'])->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_tag' => 'required|string|max:50|unique:tb_asset',
            'nama_asset' => 'required|string|max:255',
            'no_seri' => 'nullable|string|max:100',
            'id_model' => 'required|exists:tb_model,id_model',
            'id_status' => 'required|exists:tb_status,id_status',
            'id_pengguna' => 'nullable|exists:tb_pengguna,id_pengguna',
            'id_lokasi' => 'nullable|exists:tb_lokasi,id_lokasi',
            'assign_to' => 'nullable|integer',
            'assign_type' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        $asset = Asset::create($validated);

        // Log creation
        AssetLog::create([
            'id_asset' => $asset->id_asset,
            'id_status' => $asset->id_status,
            'assign_to' => $asset->assign_to,
            'assign_type' => $asset->assign_type,
            'keterangan' => 'Aset dibuat',
        ]);

        $this->activityLog('asset', 'membuat baru', $asset->id_asset, $asset->assign_to, $asset->assign_type);

        return response()->json($asset->load(['model.manufaktur', 'model.kategori', 'status', 'pengguna', 'lokasi']), 201);
    }

    public function show(Asset $asset)
    {
        return $asset->load(['model.manufaktur', 'model.kategori', 'status', 'pengguna', 'lokasi', 'logs.status', 'files', 'komponens.komponen.model']);
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'asset_tag' => ['required', 'string', 'max:50', \Illuminate\Validation\Rule::unique('tb_asset')->ignore($asset->id_asset, 'id_asset')],
            'nama_asset' => 'required|string|max:255',
            'no_seri' => 'nullable|string|max:100',
            'id_model' => 'required|exists:tb_model,id_model',
            'id_status' => 'required|exists:tb_status,id_status',
            'id_pengguna' => 'nullable|exists:tb_pengguna,id_pengguna',
            'id_lokasi' => 'nullable|exists:tb_lokasi,id_lokasi',
            'assign_to' => 'nullable|integer',
            'assign_type' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        $asset->update($validated);

        AssetLog::create([
            'id_asset' => $asset->id_asset,
            'id_status' => $asset->id_status,
            'assign_to' => $asset->assign_to,
            'assign_type' => $asset->assign_type,
            'keterangan' => 'Aset diubah',
        ]);

        $this->activityLog('asset', 'mengubah', $asset->id_asset, $asset->assign_to, $asset->assign_type);

        return response()->json($asset->load(['model.manufaktur', 'model.kategori', 'status', 'pengguna', 'lokasi']));
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return response()->noContent();
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'id_asset' => 'required|exists:tb_asset,id_asset',
            'id_status' => 'required|exists:tb_status,id_status',
            'assign_to' => 'required|integer',
            'assign_type' => 'required|string|in:pengguna,lokasi',
        ]);

        $asset = Asset::findOrFail($validated['id_asset']);
        $asset->update([
            'id_status' => $validated['id_status'],
            'assign_to' => $validated['assign_to'],
            'assign_type' => $validated['assign_type'],
            'id_pengguna' => $validated['assign_type'] === 'pengguna' ? $validated['assign_to'] : null,
            'id_lokasi' => $validated['assign_type'] === 'lokasi' ? $validated['assign_to'] : null,
        ]);

        AssetLog::create([
            'id_asset' => $asset->id_asset,
            'id_status' => $validated['id_status'],
            'assign_to' => $validated['assign_to'],
            'assign_type' => $validated['assign_type'],
            'keterangan' => 'Checkout',
        ]);

        $this->activityLog('asset', 'checkout', $asset->id_asset, $validated['assign_to'], $validated['assign_type']);

        return response()->json($asset->load(['model', 'status', 'pengguna', 'lokasi']));
    }

    public function checkin(Request $request)
    {
        $validated = $request->validate([
            'id_asset' => 'required|exists:tb_asset,id_asset',
            'id_status' => 'required|exists:tb_status,id_status',
        ]);

        $asset = Asset::findOrFail($validated['id_asset']);
        $asset->update([
            'id_status' => $validated['id_status'],
            'assign_to' => null,
            'assign_type' => null,
            'id_pengguna' => null,
            'id_lokasi' => null,
        ]);

        AssetLog::create([
            'id_asset' => $asset->id_asset,
            'id_status' => $validated['id_status'],
            'assign_to' => null,
            'assign_type' => null,
            'keterangan' => 'Checkin',
        ]);

        $this->activityLog('asset', 'checkin', $asset->id_asset, null, null);

        return response()->json($asset->load(['model', 'status']));
    }

    public function uploadFile(Request $request, Asset $asset)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg,png,gif|max:1024',
        ]);

        $file = $request->file('file');
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/assets', $filename, 'public');

        $assetFile = AssetFile::create([
            'id_asset' => $asset->id_asset,
            'nama_file' => $file->getClientOriginalName(),
            'path' => $path,
        ]);

        return response()->json($assetFile, 201);
    }

    public function deleteFile(Asset $asset, AssetFile $file)
    {
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return response()->noContent();
    }

    public function downloadFile(AssetFile $file)
    {
        return Storage::disk('public')->download($file->path, $file->nama_file);
    }

    public function generateTag()
    {
        $year = date('Y');
        $lastAsset = Asset::where('asset_tag', 'like', "ASSIT-{$year}%")
            ->orderBy('asset_tag', 'desc')
            ->first();

        if ($lastAsset) {
            $lastNumber = intval(substr($lastAsset->asset_tag, -6));
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '000001';
        }

        return response()->json(['asset_tag' => "ASSIT-{$year}{$newNumber}"]);
    }

    public function exportCsv()
    {
        $assets = Asset::with(['model'])->get();

        $csv = "asset_tag,nama_asset,no_seri,nama_model\n";
        foreach ($assets as $asset) {
            $csv .= "{$asset->asset_tag},{$asset->nama_asset},{$asset->no_seri},{$asset->model->nama_model}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="assets.csv"');
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
