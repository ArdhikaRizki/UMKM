<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use App\Livewire\Admin\Pos;
use App\Livewire\Auth\Login;
// use App\Livewire\Auth\Logout;
use App\Livewire\Public\Katalog;

// each you change the route in web.php dont forget run this
// php artisan route:clear 
# kiww


//this is for all user even without auth #kiw
Route::get('/', Katalog::class)->name('home');
Route::get('/login', Login::class)->name('login')->middleware('guest');

//this is for admin and staff in this middleware using default middleware from laravel #kiw 
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Pos::class)->name('dashboard');
    Route::get('/logout', function(){
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});


// this is kinda complicated to add new auth (custom auth) into our project
// first u type in terminal php artisan make:middleware IsAdmin - this is make a new file in app/Http?middleware?IsAdmin.php
// in that file u configure a logic, i just using simple logic to check if u not admin role u will abort on this route
// and then u configure boostrap/app.php
// search with middleware and make alias name in that file
// finish broww #kiw

Route::middleware(['auth', 'admin'])->group(function () {

});
