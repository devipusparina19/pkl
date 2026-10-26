<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdmin\MasterDataController;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'publicIndex'])
    ->name('public.dashboard');

Route::get('/dashboard-publik', [DashboardController::class, 'publicIndex'])
    ->name('public.dashboard.alternate');

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::get('/login/{role}', [AuthController::class, 'showRoleLogin'])
        ->whereIn('role', ['super-admin', 'admin', 'pimpinan'])
        ->name('login.role');

    Route::post('/login/{role}', [AuthController::class, 'login'])
        ->whereIn('role', ['super-admin', 'admin', 'pimpinan'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Semua halaman yang memerlukan login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Dashboard internal
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Peserta: hanya Admin
    | Tambah manual dinonaktifkan.
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::get('/participants', [ParticipantController::class, 'index'])
            ->name('participants.index');

        Route::get('/participants/{participant}/edit', [ParticipantController::class, 'edit'])
            ->whereNumber('participant')
            ->name('participants.edit');

        Route::get('/participants/{participant}', [ParticipantController::class, 'show'])
            ->whereNumber('participant')
            ->name('participants.show');

        Route::put('/participants/{participant}', [ParticipantController::class, 'update'])
            ->whereNumber('participant')
            ->name('participants.update');

        Route::patch('/participants/{participant}', [ParticipantController::class, 'update'])
            ->whereNumber('participant')
            ->name('participants.update.patch');

        Route::delete('/participants/{participant}', [ParticipantController::class, 'destroy'])
            ->whereNumber('participant')
            ->name('participants.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Pelatihan: hanya Admin
    | Tambah manual dinonaktifkan.
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::get('/trainings', [TrainingController::class, 'index'])
            ->name('trainings.index');

        Route::get('/trainings/{training}/edit', [TrainingController::class, 'edit'])
            ->whereNumber('training')
            ->name('trainings.edit');

        Route::put('/trainings/{training}', [TrainingController::class, 'update'])
            ->whereNumber('training')
            ->name('trainings.update');

        Route::patch('/trainings/{training}', [TrainingController::class, 'update'])
            ->whereNumber('training')
            ->name('trainings.update.patch');

        Route::delete('/trainings/{training}', [TrainingController::class, 'destroy'])
            ->whereNumber('training')
            ->name('trainings.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Import Excel: hanya Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::get('/imports', [ImportController::class, 'index'])
            ->name('imports.index');

        Route::post('/imports', [ImportController::class, 'store'])
            ->name('imports.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Laporan dan ekspor: Admin dan Pimpinan
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,pimpinan')->group(function () {

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/export', [ReportController::class, 'export'])
            ->name('reports.export');
    });

    /*
    |--------------------------------------------------------------------------
    | Super Admin: dashboard dan pengelolaan akun
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:super_admin')
        ->prefix('super-admin')
        ->name('super-admin.')
        ->group(function () {

            Route::get('/dashboard', [SuperAdminController::class, 'index'])
                ->name('dashboard');

            Route::get('/users', [SuperAdminController::class, 'users'])
                ->name('users.index');

            Route::get('/users/create', [SuperAdminController::class, 'createUser'])
                ->name('users.create');

            Route::post('/users', [SuperAdminController::class, 'storeUser'])
                ->name('users.store');

            Route::get('/users/{user}/edit', [SuperAdminController::class, 'editUser'])
                ->whereNumber('user')
                ->name('users.edit');

            Route::patch('/users/{user}/status', [SuperAdminController::class, 'toggleUserStatus'])
                ->whereNumber('user')
                ->name('users.status');

            Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])
                ->whereNumber('user')
                ->name('users.update');

            Route::delete('/users/{user}', [SuperAdminController::class, 'destroyUser'])
                ->whereNumber('user')
                ->name('users.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | Master data: hanya Super Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:super_admin')
        ->prefix('super-admin/master-data')
        ->name('super-admin.master-data.')
        ->group(function () {

            $categories = [
                'provinces',
                'districts',
                'funding-sources',
                'training-years',
                'health-worker-types',
            ];

            Route::get('/{category}', [MasterDataController::class, 'index'])
                ->whereIn('category', $categories)
                ->name('index');

            Route::get('/{category}/create', [MasterDataController::class, 'create'])
                ->whereIn('category', $categories)
                ->name('create');

            Route::post('/{category}', [MasterDataController::class, 'store'])
                ->whereIn('category', $categories)
                ->name('store');

            Route::get('/{category}/{id}/edit', [MasterDataController::class, 'edit'])
                ->whereIn('category', $categories)
                ->whereNumber('id')
                ->name('edit');

            Route::put('/{category}/{id}', [MasterDataController::class, 'update'])
                ->whereIn('category', $categories)
                ->whereNumber('id')
                ->name('update');

            Route::patch('/{category}/{id}/status', [MasterDataController::class, 'toggleStatus'])
                ->whereIn('category', $categories)
                ->whereNumber('id')
                ->name('status');

            Route::delete('/{category}/{id}', [MasterDataController::class, 'destroy'])
                ->whereIn('category', $categories)
                ->whereNumber('id')
                ->name('destroy');
        });
});