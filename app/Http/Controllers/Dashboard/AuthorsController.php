<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Traits\AuthorBookTrait;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

class AuthorsController extends Controller
{

    use AuthorBookTrait;

    public function index()
    {
        $authors = Author::select()->orderBy('id','DESC') -> paginate(PAGINATION_COUNT);
        return view('dashboard.authors.index', compact('authors'));
    }

    public function create()
    {
        $books = Book::select('id', 'title')->orderBy('title','ASC')->get();
        return view('dashboard.authors.create', compact('books'));
    }

    public function store(AuthorRequest $request)
    {

        try {

            $filePath = "";
            if ($request->has('photo')) {
                $filePath = uploadImage('authors', $request->photo);
            }

            DB::beginTransaction();

            $author = Author::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'photo' => $filePath,
            ]);

            $this->addBooks($author, $request->books);

            DB::commit();
            return redirect()->route('admin.authors')->with(['success' => 'Author was added successfully.']);

        } catch (\Exception $ex) {
            DB::rollback();
            deleteImage($filePath);
            return redirect()->route('admin.authors')->with(['error' => 'Something went wrong.']);
        }

    }


    public function edit($id)
    {

        $author = Author::find($id);

        if (!$author)
            return redirect()->route('admin.authors')->with(['error' => 'This author was not found. ']);

        $books = $author->books;
        return view('dashboard.authors.edit', compact('author', 'books'));

    }


    public function update($id, AuthorRequest $request)
    {
        try {

            $author = Author::find($id);

            if (!$author)
                return redirect()->route('admin.authors')->with(['error' => 'This author was not found.']);

            DB::beginTransaction();

            if ($request->has('photo')) {
                $filePath = uploadImage('authors', $request->photo);
                deleteImage($author->photo);
                $author->update([
                    'photo' => $filePath,
                ]);

            }

            $data = $request->except('photo', '_token', 'id', 'books');

            Author::where('id', $id)
                ->update(
                    $data
                );

            $this->addBooks($author, $request->books);

            DB::commit();


            return redirect()->route('admin.authors')->with(['success' => 'Author was updated successfully.']);
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.authors')->with(['error' => 'Something went wrong.']);
        }

    }

    function addBooks(Author $author, $books)
    {
        $books = collect($books)->keyBy(function ($item) {
            return $item;
        });

        if(!$author->books()->exists()) {
            /** @var Collection $authorBook */
            $authorBook = $author->books->keyBy('id');
            if($authorBook) {
                $syncIds = $authorBook->intersectByKeys($books)->pluck('id')->toArray();
                if($books) {
                    $booksToAttach = $books->diffKeys($authorBook);
                    foreach ($booksToAttach as $key => $value)
                    {
                        $book = Book::find($key);
                        $syncIds[] = $book->id;
                    }
                }
            }
        } else {
            $syncIds = [];
            foreach ($books as $key => $value) {
                $book = Book::find($value);
                $syncIds[] = $book->id;
            }

        }
        $author->books()->sync($syncIds);
    }

    public function getAuthor(Request $request)
    {
        return $this->getAjax($request, Author::class, 'last_name');
    }


    public function destroy($id)
    {

        try {
            //get specific categories and its translations
            $author = Author::find($id);

            if (!$author)
                return redirect()->route('admin.authors')->with(['error' => 'This author was not found.']);

            deleteImage($author->photo);
            $author->delete();

            return redirect()->route('admin.authors')->with(['success' => 'Author was deleted successfully.']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.authors')->with(['error' => 'Something went wrong.']);
        }
    }
}
