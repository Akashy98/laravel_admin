<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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

// Admin Routes
Route::prefix('admin')->group(function () {
    // Guest routes (login)
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');

    // Protected admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/example', [AdminController::class, 'example'])->name('admin.example');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::post('/users/{id}/make-admin', [AdminController::class, 'makeAdmin'])->name('admin.users.make-admin');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});
