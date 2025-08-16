<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Posts;
use App\Livewire\AllPostsView;  

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::get('/posts', Posts::class);
Route::get('/AllPostsView', AllPostsView::class);

Route::get('/all-posts', function () {
    return view('all-posts');
})->name('all-posts');

require __DIR__.'/auth.php';
