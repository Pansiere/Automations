<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportarDB\Index;
use App\Http\Controllers\ImportarDB\LoginController;

Route::get('/login', [LoginController::class, 'get'])->name('login.get');
Route::post('/login', [LoginController::class, 'post'])->name('login.post');

// Route::middleware(['PRECISO-CRIAR-O-PHPZAO'])->group(function () {
    // Route::get('/', [Index::class, ''])->name('index');
// });
