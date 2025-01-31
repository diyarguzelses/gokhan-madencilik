<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Frontend\CommunicationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Frontend\DefaultPageController;
use App\Http\Controllers\Frontend\HomePageController;
use App\Http\Controllers\Menu\MenuController;
use App\Http\Controllers\Projects\ProjectController;
use App\Http\Controllers\Settings\SettingsController;
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


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth.admin');

Route::prefix('admin')->middleware('auth.admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Menü Yönetimi
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    Route::get('/menus/data', [MenuController::class, 'getData'])->name('menus.data');

    // Ayarlar
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Kategoriler
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/data', [CategoryController::class, 'data'])->name('categories.data');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Projeler
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/data', [ProjectController::class, 'data'])->name('projects.data');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});





//HomePage Route
Route::get('/index',[HomePageController::class,'index'])->name('homePage.index');

//Communication Route
Route::get('/communication',[CommunicationController::class,'index'])->name('communication.index');
Route::post('/communication',[CommunicationController::class,'sendMessage'])->name('communication.sendMessage');

//DefaultPage Route
Route::get('defaultPage',[DefaultPageController::class,'index'])->name('defaultPage.index');
