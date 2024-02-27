<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');


Route::middleware(['auth', 'verified'])->group(function () {
    // Route for Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Route for Books
    |--------------------------------------------------------------------------
    */
    Route::controller(BookController::class)->group(function () {
        Route::name('book.')->group(function () {
            Route::get('/books', 'showBook')->name('showBook');
            Route::get('/books/add', 'addBook')->name('addBook');
            Route::post('/books/store', 'storeBook')->name('storeBook');
            Route::get('/books/{books}/edit', 'editBook')->name('editBook');
            Route::put('/books/{books}/update', 'updateBook')->name('updateBook');
            Route::delete('/books/{books}/delete', 'deleteBook')->name('deleteBook');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Route for Author
    |--------------------------------------------------------------------------
    */
    Route::controller(AuthorController::class)->group(function () {
        Route::name('author.')->group(function () {
            Route::get('/authors/', 'showAuthor')->name('showAuthor');
            Route::get('/authors/add', 'addAuthor')->name('addAuthor');
            Route::post('/authors/store', 'storeAuthor')->name('storeAuthor');
            Route::get('/authors/{authors}/edit', 'editAuthor')->name('editAuthor');
            Route::put('/authors/{authors}/update', 'updateAuthor')->name('updateAuthor');
            Route::delete('/authors/{books}/delete', 'deleteAuthor')->name('deleteAuthor');
        });
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
