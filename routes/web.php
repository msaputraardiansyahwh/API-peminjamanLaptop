<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanLaptopController;


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

Route::get('/', function () {
    return view('welcome');
});

//ambil semua data
Route::get('/laptopall', [peminjamanLaptopController::class, 'index']);
//tambah data baru
Route::post('/laptop/store', [peminjamanLaptopController::class, 'store']);
//generate token csrsf
Route::get('/generate-token', [peminjamanLaptopController::class, 'createToken']);
Route::get('/laptop/{id}', [peminjamanLaptopController::class, 'show']);
Route::patch('/laptop/update/{id}' , [peminjamanLaptopController::class, 'update']);
Route::delete('/laptop/delete/{id}' , [peminjamanLaptopController::class, 'destroy']);
// menampilkan seluruh data 
Route::get('laptop/show/trash', [peminjamanLaptopController::class, 'trash']);
// untuk mengembalikan data yang telah terhapus 
Route::get('/laptop/trash/restore/{id}', [peminjamanLaptopController::class, 'restore']);
// menghapus data tertentu 
Route::get('/laptop/trash/delete/permanent/{id}', [peminjamanLaptopController::class, 'permanenDelete']);
