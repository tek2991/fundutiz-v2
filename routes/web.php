<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FundController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\FinancialYearController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Routes
    Route::resource('user', UserController::class)->only([
        'index', 'show', 'create', 'store', 'edit', 'update'
    ]);
    Route::delete('user/{user}/detatch-role/{role}', [UserController::class, 'detatchRole'])->name('user.detatchRole');
    Route::put('user/{user}/attach-role', [UserController::class, 'attachRole'])->name('user.attachRole');
    // Role Routes
    Route::resource('role', RoleController::class)->only([
        'index', 'show', 'create', 'store', 'edit', 'update'
    ]);
    Route::delete('role/{role}/detatch-permission/{permission}', [RoleController::class, 'detatchPermission'])->name('role.detatchPermission');
    Route::put('role/{role}/attach-permission', [RoleController::class, 'attachPermission'])->name('role.attachPermission');


    Route::resource('financialYear', FinancialYearController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);

    Route::resource('office', OfficeController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);
    Route::delete('office/{office}/detatch-fund/{fund}', [OfficeController::class, 'detatchFund'])->name('office.detatchFund');
    Route::put('office/{office}/attach-fund', [OfficeController::class, 'attachFund'])->name('office.attachFund');

    Route::resource('fund', FundController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);
    Route::delete('fund/{fund}/detatch-office/{office}', [FundController::class, 'detatchOffice'])->name('fund.detatchOffice');
    Route::put('fund/{fund}/attach-office', [FundController::class, 'attachOffice'])->name('fund.attachOffice');
});
