<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\ManufakturController;
use App\Http\Controllers\Api\ModelProdukController;
use App\Http\Controllers\Api\DepartemenController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\AsesorisController;
use App\Http\Controllers\Api\InventoriController;
use App\Http\Controllers\Api\KomponenController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\ProfilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute Autentikasi (Publik)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Master Data
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('kategoris', KategoriController::class);
    Route::get('/tipes', [KategoriController::class, 'tipes']);
    Route::apiResource('manufakturs', ManufakturController::class);
    Route::apiResource('model-produks', ModelProdukController::class);
    Route::apiResource('departemens', DepartemenController::class);
    Route::apiResource('lokasis', LokasiController::class);
    Route::apiResource('penggunas', PenggunaController::class);
    Route::get('/penggunas/{pengguna}/detail', [PenggunaController::class, 'detail']);

    // Asset
    Route::apiResource('assets', AssetController::class);
    Route::post('/assets/checkout', [AssetController::class, 'checkout']);
    Route::post('/assets/checkin', [AssetController::class, 'checkin']);
    Route::post('/assets/{asset}/upload', [AssetController::class, 'uploadFile']);
    Route::delete('/assets/{asset}/files/{file}', [AssetController::class, 'deleteFile']);
    Route::get('/assets/files/{file}/download', [AssetController::class, 'downloadFile']);
    Route::get('/assets/generate-tag', [AssetController::class, 'generateTag']);
    Route::get('/assets/export/csv', [AssetController::class, 'exportCsv']);

    // Asesoris
    Route::get('/asesoris', [AsesorisController::class, 'index']);
    Route::get('/asesoris/{asesoris}', [AsesorisController::class, 'show']);
    Route::post('/asesoris/stok-masuk', [AsesorisController::class, 'stokMasuk']);
    Route::post('/asesoris/checkout', [AsesorisController::class, 'checkout']);
    Route::post('/asesoris/checkin', [AsesorisController::class, 'checkin']);
    Route::get('/asesoris/history/masuk', [AsesorisController::class, 'stokMasukHistory']);
    Route::get('/asesoris/history/keluar', [AsesorisController::class, 'stokKeluarHistory']);

    // Inventori
    Route::get('/inventori', [InventoriController::class, 'index']);
    Route::get('/inventori/{inventori}', [InventoriController::class, 'show']);
    Route::post('/inventori/stok-masuk', [InventoriController::class, 'stokMasuk']);
    Route::post('/inventori/checkout', [InventoriController::class, 'checkout']);
    Route::get('/inventori/history/masuk', [InventoriController::class, 'stokMasukHistory']);
    Route::get('/inventori/history/keluar', [InventoriController::class, 'stokKeluarHistory']);

    // Komponen
    Route::get('/komponen', [KomponenController::class, 'index']);
    Route::get('/komponen/{komponen}', [KomponenController::class, 'show']);
    Route::post('/komponen/stok-masuk', [KomponenController::class, 'stokMasuk']);
    Route::post('/komponen/checkout', [KomponenController::class, 'checkout']);
    Route::post('/komponen/checkin', [KomponenController::class, 'checkin']);
    Route::get('/komponen/history/masuk', [KomponenController::class, 'stokMasukHistory']);
    Route::get('/komponen/history/keluar', [KomponenController::class, 'stokKeluarHistory']);

    // Dashboard & Laporan
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/laporan/aktivitas', [LaporanController::class, 'aktivitas']);

    // Profil
    Route::get('/profil', [ProfilController::class, 'index']);
    Route::put('/profil', [ProfilController::class, 'update']);
    Route::put('/profil/password', [ProfilController::class, 'updatePassword']);
    Route::post('/profil/avatar', [ProfilController::class, 'uploadAvatar']);
});
