<?php

use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\MachineController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\UserController;
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
use Illuminate\Support\Facades\Artisan;
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
/*Route::get('/migrate-refresh', function () {
    try {
        // Artisan komutunu çalıştır
        Artisan::call('migrate:fresh --seed');

        // Çıktıyı döndür
        return '<pre>' . Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});*/
// Communication Route (Öncelikli)

Route::get('/communication', [CommunicationController::class, 'index'])->name('communication.index');
Route::post('/sendMessage', [CommunicationController::class, 'sendMessage'])->name('communication.sendMessage');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth.admin');


Route::prefix('admin')->middleware('auth.admin')->name('admin.')->group(function () {

    // 📌 USERS
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');


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
        Route::delete('/page-images/{id}', [PageController::class, 'deleteImage'])
            ->name('deleteImage');

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


// Projects Route (Öncelikli)
Route::get('/completedProjects', [ProjectPageController::class, 'completedProjects'])->name('completedProjects.index');
Route::get('/continuingProjects', [ProjectPageController::class, 'continuingProjects'])->name('continuingProjects.index');
Route::get('/detail/{slug}', [ProjectPageController::class, 'detail'])->name('project.detail');

// MachinePark Route (Öncelikli)
Route::get('/machinePark', [MachineParkController::class, 'index'])->name('machineParks.index');

// NewsPage Route (Öncelikli)
Route::get('/news', [NewsPageController::class, 'index'])->name('news.index');
Route::get('/news/detail/{slug}', [NewsPageController::class, 'detail'])->name('news.detail');

// Career Route (Career)
Route::get('/career', [App\Http\Controllers\Frontend\CareerController::class, 'index'])->name('career.index');

// DefaultPage Route (En Sona Alındı ve Çakışmalar Önendi)
Route::get('/{slug}', [DefaultPageController::class, 'handleMenu'])->name('menu.handle');
