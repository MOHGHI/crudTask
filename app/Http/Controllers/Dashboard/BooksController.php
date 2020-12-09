<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Traits\AuthorBookTrait;
use App\Http\Traits\BookTrait;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use DB;
use Response;

class BooksController extends Controller
{
    use AuthorBookTrait;
    use BookTrait;

    public function index()
    {
        $books = Book::select()->orderBy('id','DESC') -> paginate(PAGINATION_COUNT);
        return view('dashboard.books.index', compact('books'));
    }

    public function create()
    {
        return view('dashboard.books.create');
    }

    public function store(BookRequest $request)
    {
        try {

            $filePath = "";
            if ($request->has('photo')) {
                $filePath = uploadImage('books', $request->photo);
            }


            DB::beginTransaction();
            $book = Book::create([
                'title' => $request->title,
                'description' => $request->description,
                'year' => $request->year,
                'photo' => $filePath,
            ]);

            $this->addAuthors($book, $request->authors);
            DB::commit();
            return redirect()->route('admin.books')->with(['success' => 'Book was added successfully.']);

        } catch (\Exception $ex) {
            return $ex;
            DB::rollback();
            deleteImage($filePath);
            return redirect()->route('admin.books')->with(['error' => 'Something went wrong.']);
        }

    }


    public function edit($id)
    {

        $book = Book::find($id);
        $authors = $book->authors;

        if (!$book)
            return redirect()->route('admin.books')->with(['error' => 'This book was not found. ']);
        return view('dashboard.books.edit', compact('book', 'authors'));

    }


    public function update($id, BookRequest $request)
    {
        try {
            $book = Book::find($id);
            if (!$book)
                return redirect()->route('admin.books')->with(['error' => 'This book was not found.']);

            if ($request->has('photo')) {
                $filePath = uploadImage('books', $request->photo);
                deleteImage($book->photo);
                $book->update([
                    'photo' => $filePath,
                ]);
            }

            $data = $request->except('photo', '_token', 'id', 'authors');
            Book::where('id', $id)
                ->update(
                    $data
                );
            $this->addAuthors($book, $request->authors);
            DB::commit();
            return redirect()->route('admin.books')->with(['success' => 'Book was updated successfully.']);
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.books')->with(['error' => 'Something went wrong.']);
        }

    }

    public function getBook(Request $request)
    {
        return $this->getAjax($request, Book::class, 'title');
    }

    public function destroy($id)
    {

        try {
            $book = Book::find($id);
            if (!$book)
                return redirect()->route('admin.books')->with(['error' => 'This book was not found.']);

            deleteImage($book->photo);
            $book->delete();
            return redirect()->route('admin.books')->with(['success' => 'Book was deleted successfully.']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.books')->with(['error' => 'Something went wrong.']);
        }
    }
}
