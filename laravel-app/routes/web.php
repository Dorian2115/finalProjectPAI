<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OpenLibraryController;

Route::get('/', function () {
    $books = \App\Models\Book::where('user_id', auth()->id())->latest()->get();
    $favoriteBooks = \App\Models\Book::where('user_id', auth()->id())
        ->where('is_favorite', true)
        ->latest()
        ->get();
    return view('main', compact('books', 'favoriteBooks'));
})->middleware('auth')->name('main');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.submit');

Route::middleware('auth')->group(function () {
    // OpenLibrary routes MUST be first (before {book} parameter routes)
    Route::get('/books/search', [OpenLibraryController::class, 'search'])->name('books.search');
    Route::get('/books/search/api', [OpenLibraryController::class, 'searchApi'])->name('books.search.api');
    Route::post('/books/import', [OpenLibraryController::class, 'import'])->name('books.import');
    
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::post('/books/{book}/favorite', [BookController::class, 'toggleFavorite'])->name('books.favorite');
});