<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Home\AdminHomeController;
use App\Http\Controllers\Admin\Setting\AdminSettingController;
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

Route::get('/', function () {
    return view('welcome');
});

// Route::controller(AdminHomeController::class)
//     ->group(function () {
//         Route::get('/admin', 'index')->name('admin.home');
//     });

// Route::controller(AdminAuthController::class)
//     ->group(function () {
//         Route::get('/admin/login', 'login')->name('admin.login');
//     });


################ Auth ################
Route::middleware('guest:admin')
    ->prefix('admin')
    ->group(function () {
        Route::controller(AdminAuthController::class)
            ->group(function () {
                Route::match(['get', 'post'], '/login', 'login')->name('admin.login');
            });
    });
################ 관리자 라우팅
Route::middleware('auth.check')
    ->prefix('admin')
    ->group(function () {
        ########### Auth
        Route::controller(AdminAuthController::class)
            ->group(function () {
                Route::get('/logout', 'logout')->name('admin.logout');
                Route::get('/account', 'account')->name('admin.auth.account');
                Route::post('/data', 'data')->name('admin.auth.data');
            });
        ############# Home
        Route::controller(AdminHomeController::class)
            ->group(function () {
                Route::get('/', 'index')->name('admin.home');
            });
        ############# Setting
        Route::controller(AdminSettingController::class)
            ->prefix('member')
            ->group(function () {
                Route::get('/', 'index')->name('admin.setting.member');
                Route::get('/write', 'write')->name('admin.setting.member.add');
                Route::get('/{id}', 'view')->name('admin.setting.member.view');
                Route::post('/data', 'data')->name('admin.setting.data');
            });
    });
