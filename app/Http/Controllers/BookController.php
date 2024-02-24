<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;
use App\Models\Image;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function showBook()
    {
        $books = Book::with(['authors', 'images'])->get();
        return Inertia::render('Book/index', [
            'books' => $books,
            'links' => [
                public_path('storage') => storage_path('app/public/images')
            ]
        ]);
    }

    public function addBook()
    {
        $authors = Author::all();
        return Inertia::render('Book/add', [
            'authors' => $authors,
        ]);
    }

    public function storeBook(BookRequest $request)
    {
        $request->validated();

        // Get the book image and save it to the server
        $book = new Book();
        $book->book_title = $request['book_title'];
        $book->date_published = $request['date_published'];
        $book->save();

        $book->authors()->attach($request['author_id']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $image->storeAs('public/images', $filename);

            $imageModel = new Image();
            $imageModel->filename = $filename;
            $book->images()->save($imageModel);

            return redirect(route('book.showBook'))->with('success', 'The book has been added!');
        } else {
            return redirect(route('book.showBook'))->with('success', 'The book has been added!');
        }
    }

    public function editBook($id)
    {
        $books = Book::with(['authors', 'images'])->findOrFail($id);
        $authors = Author::all();
        return Inertia::render('Book/edit', [
            'books' => $books,
            'authors' => $authors,
            'links' => [
                public_path('storage') => storage_path('app/public/images')
            ],
            'success' => session('success'),
            'error' => session('error'),
        ]);
    }

    public function updateBook(BookRequest $request, Book $books)
    {
        $request->validated();
        $books->update([
            'book_title' => $request['book_title'],
            'date_published' => $request['date_published'],
        ]);

        $books->authors()->sync([$request['author_id']]);

        // If the updated data has image attached, execute this code
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $image->storeAs('public/images', $filename);

            if ($books->images()) {
                // Update the filename of the existing image
                $books->images()->updateOrCreate(
                    [],
                    ['filename' => $filename]
                );
            }
        }
        return redirect(route('book.showBook'))->with('success', 'The book has been updated!');
    }

    public function deleteBook($id)
    {
        $book = Book::findOrFail($id);

        $book->images()->delete();
        $book->delete();

        return redirect(route('book.showBook'))->with('success', 'The book has been deleted!');
    }
}
