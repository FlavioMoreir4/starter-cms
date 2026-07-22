<?php

use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\FormController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{project:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/pagina/{page:slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/formularios/{form:slug}', [FormController::class, 'show'])->name('forms.show');
Route::post('/formularios/{form:slug}', [FormController::class, 'submit'])->name('forms.submit');

// Somete para referencias do start-kit
Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

// Somete para referencias do start-kit
require __DIR__.'/settings.php';
