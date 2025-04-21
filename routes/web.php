<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route Halaman Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

// Route Halaman Data Finansial
Route::get('/', [ExpenseController::class, 'index']); // Menampilkan kesuluruhan data
Route::get('/create_ajax', [ExpenseController::class, 'create_ajax']); // Menampilkan form tambah data
Route::post('/ajax', [ExpenseController::class, 'store_ajax']); // Menyimpan data finansial
Route::get('/{id}', [ExpenseController::class, 'show']); // Menampilkan detail data berdasarkan id, // jika ada path setelah id maka akan dianggap path yang brbeda
Route::get('/{id}/edit_ajax', [ExpenseController::class, 'edit_ajax']); // Menampilkan form update data
Route::put('/{id}/update_ajax', [ExpenseController::class, 'update_ajax']); // Memperbarui data berdasarkan id
Route::get('/{id}/delete_ajax', [ExpenseController::class, 'confirm_ajax']); // menampilkan alert konfirmasi untuk menghapus data
Route::delete('/{id}/delete_ajax', [ExpenseController::class, 'delete_ajax']); // menghapus data berdasarkan id
