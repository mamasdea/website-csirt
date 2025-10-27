<?php

use App\Livewire\Backend\Menu;
use App\Livewire\Backend\Pages;
use App\Livewire\Backend\Article;
use App\Livewire\Backend\Category;
use App\Livewire\Backend\FilePanduan;
use App\Livewire\Backend\SettingWeb; // Import SettingWeb component
use App\Livewire\Frontend\Beranda;
use App\Livewire\Backend\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Frontend\Page\Detail;
use App\Livewire\Frontend\Article\GridBerita;
use App\Livewire\Frontend\Article\GridArticle;
use App\Livewire\Frontend\Article\DetailArticle;
use App\Http\Controllers\ImageUploadController;
use App\Livewire\Frontend\Page\Lapor;
use App\Livewire\Auth\Login; // Import the Login Livewire component
use Illuminate\Support\Facades\Auth; // Import Auth facade

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

// Authentication Routes

Route::get('/csirt/login', Login::class)->name('login');
Route::post('/csirt/login', Login::class)->name('login.attempt'); // Add POST route for login submission
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// harus login
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/category', Category::class)->name('category');
    Route::get('/article', Article::class)->name('article');
    Route::get('/pages', Pages::class)->name('pages');
    Route::get('/menu', Menu::class)->name('menu');
    Route::get('/file-panduan', FilePanduan::class)->name('file-panduan.index');
    Route::get('/setting-web', SettingWeb::class)->name('setting-web'); // Add route for SettingWeb
    Route::post('/upload-image', [ImageUploadController::class, 'upload'])->name('upload-image');
});


// bisa diakses tanpa login
Route::get('/', Beranda::class)->name('beranda');
Route::get('/detail-pages/{slug}', Detail::class)->name('detail-pages');
Route::get('/article-detail/{slug}', DetailArticle::class)->name('articles.show');
Route::get('/article-grid', GridArticle::class)->name('articles.grid');
Route::get('/berita-grid', GridBerita::class)->name('berita.grid');
Route::get('/lapor-insiden', Lapor::class)->name('lapor-insiden');
