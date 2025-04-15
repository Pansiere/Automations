<?php

use App\Http\Controllers\ImportarDB;
use Illuminate\Support\Facades\Route;

Route::get('/', [ImportarDB::class, 'login'])->name('login');
