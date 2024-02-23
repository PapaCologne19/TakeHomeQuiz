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
        $authors = DB::table('books')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->join('images', 'books.id', '=', 'images.transactionable_id')
            ->where('images.transactionable_type', 'App\Models\Book')
            ->select('books.*', 'authors.author_name', 'images.filename')
            ->get();

        return Inertia::render('Book/index', [
            'books' => $authors,
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
        $book->author_id = $request['author_id'];
        $book->save();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $image->storeAs('public/images', $filename);

            $imageModel = new Image();
            $imageModel->filename = $filename;
            $book->images()->save($imageModel);

            return redirect(route('book.showBook'))->with('success', 'The book has been added!');
        } else {
            return redirect(route('book.showBook'))->with('error', 'The book is not added!');
        }

    }

    public function editBook($id)
    {
        $books = DB::table('books')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->join('images', 'books.id', '=', 'images.transactionable_id')
            ->where('books.id', $id)
            ->where('images.transactionable_type', 'App\Models\Book')
            ->select('books.*', 'authors.author_name', 'images.filename')
            ->first();

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
            'author_id' => $request['author_id'],
            'book_title' => $request['book_title'],
            'date_published' => $request['date_published'],
        ]);

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
            return redirect(route('book.showBook'))->with('success', 'The book has been updated!');
        }
        // Else, if the user updated only the data without images, execute this code. 
        else {
            return redirect(route('book.showBook'))->with('success', 'The book has been updated!');
        }
    }

    public function deleteBook($id)
    {
        $book = Book::findOrFail($id);

        $book->images()->delete();
        $book->delete();

        return redirect(route('book.showBook'))->with('success', 'The book has been deleted!');
    }
}
