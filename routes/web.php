<?php

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

Route::get('/', [ExpenseController::class, 'index']);
Route::get('/create_ajax', [ExpenseController::class, 'create_ajax']);
Route::post('/ajax', [ExpenseController::class, 'store_ajax']);
Route::get('/{id}', [ExpenseController::class, 'show']);
Route::get('/{id}/edit_ajax', [ExpenseController::class, 'edit_ajax']);
Route::put('/{id}/update_ajax', [ExpenseController::class, 'update_ajax']);
Route::get('/{id}/delete_ajax', [ExpenseController::class, 'confirm_ajax']);
Route::delete('/{id}/delete_ajax', [ExpenseController::class, 'delete_ajax']);

Route::get('/dashboard', function () {
    return view('dashboard');
});
