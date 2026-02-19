<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('job-postings', 'pages::job-postings.index')->name('job-postings.index');
    Route::livewire('job-postings/create', 'pages::job-postings.create')->name('job-postings.create');
    Route::livewire('job-postings/{jobPosting}', 'pages::job-postings.show')->name('job-postings.show');

    Route::livewire('gologin-profiles', 'pages::gologin-profiles.index')->name('gologin-profiles.index');
});

require __DIR__.'/settings.php';
