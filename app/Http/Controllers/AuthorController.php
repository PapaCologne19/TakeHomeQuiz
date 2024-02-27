<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Image;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Method for Showing Authors
    |--------------------------------------------------------------------------
    */
    public function showAuthor()
    {
        $authors = Author::with(['books', 'images'])->get();
        return Inertia::render('Author/index', [
            'authors' => $authors,
            'links' => [
                public_path('storage') => storage_path('app/public/images')
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Method for Showing Add Author View
    |--------------------------------------------------------------------------
    */
    public function addAuthor()
    {
        return Inertia::render('Author/add');
    }

    /*
    |--------------------------------------------------------------------------
    | Method for Storing Author to Database
    |--------------------------------------------------------------------------
    */
    public function storeAuthor(AuthorRequest $request)
    {
        $author = new Author();
        $author->author_name = $request['author_name'];
        $author->biography = $request['biography'];

        $author->save();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $image->storeAs('public/images', $filename);

            $imageModel = new Image();
            $imageModel->filename = $filename;
            $author->images()->save($imageModel);

            return redirect(route('author.showAuthor'))->with('success', 'The author has been added!');

        }
        return redirect(route('author.showAuthor'))->with('error', 'The author is not added');
    }

    /*
    |--------------------------------------------------------------------------
    | Method for Showing Edit  Author View with Data from DB
    |--------------------------------------------------------------------------
    */
    public function editAuthor(Author $authors)
    {
        $authors->load('images');
        return Inertia::render('Author/edit', [
            'authors' => $authors,
            'links' => [
                public_path('storage') => storage_path('app/public/images')
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Method for Updating Authors
    |--------------------------------------------------------------------------
    */
    public function updateAuthor(Request $request, Author $authors)
    {
        $data = $request->validate([
            'author_name' => 'required',
            'biography' => 'required',
            'image' => $request->hasFile('image') ? 'required|image|mimes:jpg,png,gif,bmp' : 'nullable',
        ]);

        $authors->update([
            'author_name' => $data['author_name'],
            'biography' => $data['biography'],
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $image->storeAs('public/images', $filename);

            if ($authors->images()) {
                // Update the filename of the existing image
                $authors->images()->updateOrCreate(
                    [],
                    ['filename' => $filename]
                );
            }
        }

        return redirect(route('author.showAuthor'))->with('success', 'The author has been updated!');
    }

    /*
    |--------------------------------------------------------------------------
    | Method for Deleting Authors based on the ID
    |--------------------------------------------------------------------------
    */
    public function deleteAuthor($id)
    {
        // Finding the Author ID
        $author = Author::findOrFail($id);

        // Deleting the author images first before deleting the author itself
        foreach ($author->books as $book) {
            $book->images()->delete();
            $book->delete();
        }

        // Deleting Author
        $author->delete();

        // Return to the index page once the process is success
        return redirect(route('author.showAuthor'))->with('success', 'The author and their associated books have been deleted!');
    }

}
