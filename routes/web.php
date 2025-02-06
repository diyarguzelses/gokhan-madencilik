<?php

use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\MachineController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Frontend\CommunicationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Frontend\DefaultPageController;
use App\Http\Controllers\Frontend\HomePageController;
use App\Http\Controllers\Frontend\MachineParkController;
use App\Http\Controllers\Frontend\NewsPageController;
use App\Http\Controllers\Frontend\ProjectPageController;
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

    // 📌 DASHBOARD
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // 📌 AYARLAR
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // 📌 SAYFA YÖNETİMİ
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/data', [PageController::class, 'getData'])->name('data');
        Route::post('/', [PageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PageController::class, 'update'])->name('update');
        Route::delete('/{id}', [PageController::class, 'destroy'])->name('destroy');
    });

    // 📌 MENÜ YÖNETİMİ
    Route::prefix('menus')->name('menus.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('/data', [MenuController::class, 'getData'])->name('data');
        Route::post('/', [MenuController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MenuController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MenuController::class, 'update'])->name('update');
        Route::delete('/{id}', [MenuController::class, 'destroy'])->name('destroy');
    });

    // 📌 KATEGORİLER
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/data', [CategoryController::class, 'data'])->name('data');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::post('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // 📌 SEKTÖRLER
    Route::prefix('sectors')->name('sectors.')->group(function () {
        Route::get('/', [SectorController::class, 'index'])->name('index');
        Route::get('/data', [SectorController::class, 'getData'])->name('data');
        Route::post('/', [SectorController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SectorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SectorController::class, 'update'])->name('update');
        Route::delete('/{id}', [SectorController::class, 'destroy'])->name('destroy');
    });

    // 📌 MAKİNE PARKI
    Route::prefix('machines')->name('machines.')->group(function () {
        Route::get('/', [MachineController::class, 'index'])->name('index');
        Route::get('/data', [MachineController::class, 'getData'])->name('data');
        Route::post('/', [MachineController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MachineController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MachineController::class, 'update'])->name('update');
        Route::delete('/{id}', [MachineController::class, 'destroy'])->name('destroy');
    });

    // 📌 HABERLER
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::get('/data', [NewsController::class, 'getData'])->name('data');
        Route::post('/', [NewsController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [NewsController::class, 'update'])->name('update');
        Route::delete('/{id}', [NewsController::class, 'destroy'])->name('destroy');
    });

    // 📌 KARİYER
    Route::prefix('career')->name('career.')->group(function () {
        Route::get('/', [CareerController::class, 'edit'])->name('edit');
        Route::post('/', [CareerController::class, 'update'])->name('update');
    });

    // 📌 PROJELER
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/data', [ProjectController::class, 'data'])->name('data');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('edit');
        Route::post('/{id}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ProjectController::class, 'destroy'])->name('destroy');
    });


});


//HomePage Route
Route::get('/', [HomePageController::class, 'index'])->name('homePage.index');
Route::get('/index', [HomePageController::class, 'index'])->name('homePage.index');

// Communication Route (Öncelikli)
Route::get('/communication', [CommunicationController::class, 'index'])->name('communication.index');
Route::post('/communication', [CommunicationController::class, 'sendMessage'])->name('communication.sendMessage');

// Projects Route (Öncelikli)
Route::get('/completedProjects', [ProjectPageController::class, 'completedProjects'])->name('completedProjects.index');
Route::get('/continuingProjects', [ProjectPageController::class, 'continuingProjects'])->name('continuingProjects.index');

// MachinePark Route (Öncelikli)
Route::get('/machinePark', [MachineParkController::class, 'index'])->name('machineParks.index');

// NewsPage Route (Öncelikli)
Route::get('/news', [NewsPageController::class, 'index'])->name('news.index');
Route::get('/news/detail', [NewsPageController::class, 'detail'])->name('news.detail');

// Career Route (Career)
Route::get('/career', [App\Http\Controllers\Frontend\CareerController::class, 'index'])->name('career.index');

// DefaultPage Route (En Sona Alındı ve Çakışmalar Önendi)
Route::get('/{slug}', [DefaultPageController::class, 'handleMenu'])->name('menu.handle');
