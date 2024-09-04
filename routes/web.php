<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('admin', 'livewire.admin')->middleware(['auth', 'verified'])->name('admin');
Route::view('admin/digital-cards', 'livewire.digital-cards.digital-cards')->middleware(['auth'])->name('admin/digital-cards');
Route::view('admin/users', 'livewire.admin.users.users')->middleware(['auth', 'verified'])->name('admin/users');
Route::view('admin/settings', 'livewire.admin.settings.settings')->middleware(['auth', 'verified'])->name('admin/settings');
Route::view('admin/website-settings', 'livewire.admin.users.users')->middleware(['auth', 'verified'])->name('admin/website-settings');
Route::view('admin/reports', 'livewire.admin.users.users')->middleware(['auth', 'verified'])->name('admin/reports');


// User end
Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');
Route::view('/profile', 'profile')->middleware(['auth'])->name('profile');
Route::view('/digital-cards', 'livewire.digital-cards.digital-cards')->middleware(['auth'])->name('digital-cards');
Route::view('/add-profile', 'livewire.digital-cards.add-profile')->middleware(['auth'])->name('add-profile');
// Route::view('/me/{card}', 'livewire.digital-cards.digital-card')->middleware(['auth']);

require __DIR__.'/auth.php';


Route::view('/{card}', 'livewire.digital-cards.digital-card');
