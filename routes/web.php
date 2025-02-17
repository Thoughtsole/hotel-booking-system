<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Notifications\UserCreateNotification;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('guest');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $props = ['title' => 'Dashboard'];
        return Inertia::render('Dashboard', $props);
    })->name('dashboard');
    Route::resource('menu', MenuController::class);
    Route::resource('role', RoleController::class);
    Route::any('role/{role}/permission', [RoleController::class, 'permission'])->name('role.permission');
    Route::resource('permission', PermissionController::class);
    Route::resource('user', UserController::class);
    Route::resource('country', CountryController::class);
    Route::resource('city', CityController::class);

    Route::get('language/{language}', function ($language) {
        session()->put('locale', $language);
        return back();
    })->name('language');

    // notification routes
    Route::get('notification', [NotificationController::class, 'index'])->name('notification');
    Route::delete('notification/{id}', [NotificationController::class, 'delete'])->name('delete.notification');
    Route::put('notification/{id}', [NotificationController::class, 'markAsRead'])->name('mark_as_read');

    Route::get('test', function () {
        $notificationUser = User::find(1);
        $notify = User::find(1);
        $notify->notify(new UserCreateNotification($notificationUser));
        echo "ok";
        die;
    });
});

Route::get('test2', function () {
    $check_in = '2023-02-26';
    $check_out = '2023-02-28';
    $result = \DB::table('rooms')->whereNotExists(function ($query) use ($check_in, $check_out) {
        $query->select('reservations.id')
            ->from('reservations')
            ->join('reservation_room', 'reservations.id', '=', 'reservation_room.reservation_id')
            ->whereRaw('rooms.id = reservation_room.room_id')
            ->where(function ($q) use ($check_in, $check_out) {
                $q->where('check_out', '>', $check_in);
                $q->where('check_in', '<', $check_out);
            })
            ->limit(1);
    })
        ->paginate(10);
    dd($result);
});

Route::get('get-rooms', [QueryController::class, 'getAvailableRoom']);
