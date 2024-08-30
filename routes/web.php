<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/add-contact', [ContactController::class, 'store']);
Route::post('/import', [ContactController::class, 'import'])->name('import');

Route::get('contacts/export', [ContactController::class, 'export'])->name('contacts.export');
Route::post('contacts/import', [ContactController::class, 'import'])->name('contacts.import');

Route::get('contacts', function () {
    return view('contacts');
});