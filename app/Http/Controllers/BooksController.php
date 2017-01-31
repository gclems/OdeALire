<?php
namespace App\Http\Controllers;
use Exception;
use App\Http\Controllers\Controller;
use App\Viewmodels\Books\BooksAddFormViewModel;
use App\Viewmodels\Books\BooksEditFormViewModel;
use App\Viewmodels\Books\BookIndexListViewmodel;
use App\Viewmodels\Books\SearchBookViewModel;
use App\Viewmodels\Books\SearchResultViewModel;
use App\Viewmodels\AjaxViewmodel;
use App\Book;
use App\Editor;
use App\Author;
use App\Serie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use GuzzleHttp\Client;

class BooksController extends Controller{
    const ISBNDBKEY = '650ATEOS';

    public function index(){
      return view('books.index', ['viewmodel'=>$this->listBooks(50, 1, 'CreatedAt', 'desc', null)]);
    }

    public function list(Request $request){
        $success = false;
        $error = null;
        $viewmodel=null;
        try{
          $viewmodel = $this->listBooks(
             $request->input('maxNumber'),
             $request->input('pageNumber'),
             $request->input('orderByColumn'),
             $request->input('orderByDirection'),
             $request->input('search'));

            $success = true;
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $viewmodel = new BookIndexListViewmodel();
        }

        return response()->json(new AjaxViewmodel(
            $success,
            $error,
            ($success ? view('books.index-books-list', ['viewmodel' => $viewmodel])->render() : null)));
    }

    private function listBooks(
      $takeMax,
      $pageNumber,
      $orderByColumn,
      $orderByDirection,
      $searchedTitle){

        $viewmodel = new BookIndexListViewmodel();

        $viewmodel->books = Book::with('authors', 'serie', 'editor')
                                ->withCount(['loans' => function($query){
                                  $query->whereDate('LentAt', '<=', Carbon::today()->toDateString())
                                        ->where(function($query){
                                          $query->whereNull('ReturnedAt')
                                                ->orWhere('ReturnedAt', '>', Carbon::today()->toDateString());
                                        });
                                }]);

        if((isset($searchedTitle) && trim($searchedTitle) !=='')){
            $viewmodel->books = $viewmodel->books->where('Title', 'like', '%' . $searchedTitle . '%');
        }

        $count = Book::count();

        $viewmodel->books = $viewmodel->books->orderBy($orderByColumn, $orderByDirection)
                                             ->skip(max(0, $pageNumber - 1) * $takeMax)
                                             ->take($takeMax)
                                             ->get();

        $viewmodel->paginationViewmodel->totalCount = $count;
        $viewmodel->paginationViewmodel->currentPageNumber = $pageNumber;
        $viewmodel->paginationViewmodel->countPerPage = $takeMax;

        return $viewmodel;
    }

    public function search(Request $request){
      $success = false;
      $error = null;
      $viewmodel=null;

      try{
        $isbn = $request->input('book-isbn');
        $text = $request->input('book-title');

        if( (!isset($isbn) || trim($isbn) === '')
          && (!isset($text) || trim($text) === '')){
            throw new Exception('Veuillez renseigner au moins un petit truc quoi.');
        }

        $viewmodel = new SearchBookViewModel();

        // Search by ISBN
        if((isset($isbn) && trim($isbn) !== '')){
          // Google API search
          try{
            $uri = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . $isbn;
            $client = new Client([
                'base_uri' => $uri,
            ]);

            $response = $client->request('GET');
            $responseBody = json_decode($response->getBody(), true);

            if($response->getStatusCode() == 200){
                if($responseBody['totalItems'] > 0){
                  $results = $this->generateResultViewModelsFromGoogleResponse($responseBody);
                  foreach($results as $result){
                    array_push($viewmodel->searchResults, $result);
                  }
                }
            }
            else{
              throw new Exception($responseBody);
            }
          }
          catch(Exception $e){
            $viewmodel->googleSearchState = false;
            $viewmodel->googleError = $e->getMessage();
          }

          // IsbnDB search
          try{
            $uri = 'http://isbndb.com/api/v2/json/'. self::ISBNDBKEY .'/book/';
            $client = new Client([
                'base_uri' => $uri,
            ]);

            $response = $client->request('GET',$isbn);
            $responseBody = json_decode($response->getBody(), true);

            if($response->getStatusCode() == 200){
              if(!isset($responseBody['error'])){
                $results = $this->generateResultViewModelsFromIsbnDBResponse($responseBody);
                foreach($results as $result){
                  array_push($viewmodel->searchResults, $result);
                }
              }
            }
            else{
              throw new Exception($responseBody);
            }
          }
          catch(Exception $e){
            $viewmodel->isbnDBSearchState = false;
            $viewmodel->isbnDBError = $e->getMessage();
          }

          // WorldCat search
          try{

          }
          catch(Exception $e){
            $viewmodel->worldCatSearchState = false;
          }
        }

        // Search by Text
        if((isset($text) && trim($text) !== ''))
        {
          // IsbnDB search
          try{
            $client = new Client([
                'base_uri' => 'http://isbndb.com/api/v2/json/'. self::ISBNDBKEY .'/books?q='.str_replace(' ', '_', $text),
            ]);

            $response = $client->request('GET');
            $responseBody = json_decode($response->getBody(), true);

            if($response->getStatusCode() == 200){
              if(!isset($responseBody['error'])){
                $results = $this->generateResultViewModelsFromIsbnDBResponse($responseBody);
                foreach($results as $result){
                  array_push($viewmodel->searchResults, $result);
                }
              }
            }
            else{
              throw new Exception($responseBody);
            }
          }
          catch(Exception $e){
            $viewmodel->isbnDBSearchState = false;
            $viewmodel->isbnDBError = $e->getMessage();
          }
        }

        $success = true;
      }
      catch(Exception $e){
        $error = $e->getMessage();
        $viewmodel = null;
      }

      return response()->json(new AjaxViewmodel(
        $success,
        $error,
        ($success ? view('books.add.step1results', ['viewmodel' => $viewmodel])->render() : null)));
    }

    private function generateResultViewModelsFromIsbnDBResponse($response){
      $array = array();

      foreach($response['data'] as $bookInfo){
        $vm = new SearchResultViewModel();
        $vm->source = 'IsbnDB';
        $vm->isbn = $bookInfo['isbn13'];
        $vm->title =  $bookInfo['title'];
        $vm->editorName = $bookInfo['publisher_name'];
        $vm->description = $bookInfo['summary'];

        foreach($bookInfo['author_data'] as $authorData)
        {
          array_push($vm->authorsNames, $authorData['name']);
        }

        array_push($array, $vm);
      }

      return $array;
    }

    private function generateResultViewModelsFromGoogleResponse($response){
      $array = array();

      if(isset($response['items'])){
        foreach($response['items'] as $bookInfo){
          $volumeInfo = $bookInfo['volumeInfo'];

          $vm = new SearchResultViewModel();
          $vm->source = 'Google';
          $vm->title = $volumeInfo['title'];
          $vm->editorName = $volumeInfo['publisher'];
          $vm->description = $volumeInfo['description'];

          foreach($volumeInfo['industryIdentifiers'] as $isbn){
            if($isbn['type'] == 'ISBN_13'){
              $vm->isbn = $isbn['identifier'];
            }
          }

          foreach($volumeInfo['authors'] as $authorData)
          {
            array_push($vm->authorsNames, $authorData);
          }

          array_push($array, $vm);
        }
      }

      return $array;
    }

    public function addForm(Request $request){
        $viewmodel = new BooksAddFormViewModel();
        $viewmodel->editors = Editor::get();
        $viewmodel->authors = Author::get();
        $viewmodel->series = Serie::get();

        // manage sent data
        $viewmodel->isbn = $request->input('isbn');
        $viewmodel->title = $request->input('title');
        $viewmodel->description = $request->input('description');

        // publisher : search in list of publishers,
        $publisherName = $request->input('publisher');
        if(isset($publisherName) && trim($publisherName) !== ''){
          $cleanName = trim(strtolower(($publisherName)));
          $i = 0;
          while($i < $viewmodel->editors->count() && $viewmodel->publisherId == null){
            $publisher = $viewmodel->editors[$i];
            if(strtolower($publisher->Name) == $cleanName){
              $viewmodel->publisherId = $publisher->Id;
            }

            $i++;
          }

          if($viewmodel->publisherId == null){
            $viewmodel->unknownPublisherName = $publisherName;
          }
        }

        // authors
        $authorsNames = $request->input('authors');
        if(isset($authorsNames) && trim($authorsNames) != ''){
          $authors = explode(';', $authorsNames);

          for($i = 0; $i < count($authors); $i++){
            $name = $authors[$i];
            $cleanName = trim(strtolower($name));

            if($cleanName != ''){
              $author = null;

              $j = 0;
              while($author == null && $j < $viewmodel->authors->count()){
                if(trim(strtolower($viewmodel->authors[$j]->Name)) == $cleanName){
                  $author = $viewmodel->authors[$j];
                }

                $j++;
              }

              if($author != null){
                array_push($viewmodel->bookAuthors, $author);
              }
              else{
                array_push($viewmodel->unknownAuthorsNames, $name);
              }
            }
          }
        }

        return view('books.add.step2', ['viewmodel' => $viewmodel]);
    }

    public function editForm($bookId){
      $book = Book::find($bookId);

      if($book != null){
        $viewmodel = new BooksEditFormViewModel();
        $viewmodel->editors = Editor::get();
        $viewmodel->authors = Author::get();
        $viewmodel->series = Serie::get();
        $viewmodel->book = $book;

        return view('books.edit', ['viewmodel' => $viewmodel]);
      }
      else{
        return redirect()->route('books');
      }
    }

    public function create(Request $request){
      $success = true;
      $error = '';
      $book = null;

      try{
        $isbn = $request->has('bookIsbn') ? $request->input('bookIsbn') : null;
        $title = $request->has('bookTitle') ? $request->input('bookTitle') : null;
        $description = $request->has('bookDescription') ? $request->input('bookDescription') : null;
        $editorId = $request->has('bookEditorId') ? $request->input('bookEditorId') : null;
        $serieId = $request->has('bookSerieId') ? $request->input('bookSerieId') : null;
        $serieNumber = $request->has('bookSerieNumber') ? $request->input('bookSerieNumber') : 0;
        $authors = $request->has('bookAuthors') ? json_decode($request->input('bookAuthors')) : [];

        if($this->IsNullOrEmptyString($isbn)
        || $this->IsNullOrEmptyString($title)
        || count($authors) == 0)
        {
          throw new Exception('Un livre doit avoir un ISBN, un titre et au moins un auteur.');
        }

        // check if book doesn't already exist
        $existingBook = Book::where('Isbn','=', $isbn)->get();
        if(!$existingBook->isEmpty()){
            throw new Exception('Cet ISBN est déjà enregistré.');
        }

        // check if editor exists
        if(isset($editorId)){
            $existingEditor = Editor::find($editorId);
            if($existingEditor==null){
                throw new Exception('L\'éditeur est introuvable.');
            }
        }

        // check if all authors exist
        $allExist = true;
        $existingAuthors = Author::whereIn('Id', $authors)->get();
        $existingIds = $existingAuthors->map(function($item, $key){
            return $item->Id;
        });

        foreach($authors as $authorId){
            if(!$existingIds->contains($authorId)){
                $allExist = false;
            }
        }

        if(!$allExist){
            throw new Exception('Un ou plusieurs auteurs n\' existent pas.');
        }

        // Save book
        $book = new Book();
        $book->Isbn = $isbn;
        $book->Title = $title;
        $book->Description = $description;
        $book->Editor_Id = $editorId;

        if($serieId != null){
          $book->Serie_Id = $serieId;
          $book->SerieNumber = $serieNumber;
        }

        $book->Creator_Id = Auth::user()->Id;
        $book->save();

        $book->authors()->attach($authors);
      }
      catch(Exception $e){
        $success = false;
        $error = $e->getMessage();
      }

      return response()->json(new AjaxViewmodel($success,$error, null));
    }

    public function update(Request $request){
        $success = true;
        $error = '';

        try{
            if(!$request->has('bookId')){
                throw new Exception("Aucun identifiant donné.");
            }

            $bookId = $request->input('bookId');
            $isbn = $request->has('bookIsbn') ? $request->input('bookIsbn') : null;
            $title = $request->has('bookTitle') ? $request->input('bookTitle') : null;
            $description = $request->has('bookDescription') ? $request->input('bookDescription') : null;
            $editorId = $request->has('bookEditorId') ? $request->input('bookEditorId') : null;
            $serieId = $request->has('bookSerieId') ? $request->input('bookSerieId') : null;
            $serieNumber = $request->has('bookSerieNumber') ? $request->input('bookSerieNumber') : 0;
            $authors = $request->has('bookAuthors') ? json_decode($request->input('bookAuthors')) : [];

            $book = Book::Find($bookId);
            if($book == null){
                throw new Exception("Livre introuvable");
            }

            if($this->IsNullOrEmptyString($isbn)
            || $this->IsNullOrEmptyString($title)
            || count($authors) == 0)
            {
              throw new Exception('Un livre doit avoir un ISBN, un titre et au moins un auteur.');
            }

            // check if book doesn't already exist
            $existingBooks = Book::where('Isbn','=', $isbn)->get();
            if(!$existingBooks->isEmpty()){
                $isntEditedBook = false;
                foreach($existingBooks as $bookToCheck){
                    $isntEditedBook = $bookToCheck->Id != $bookId;
                }

                if($isntEditedBook){
                    throw new Exception('Cet ISBN est déjà enregistré pour un autre livre.');
                }
            }

            // check if editor exists
            if(isset($editorId)){
                $existingEditor = Editor::find($editorId);
                if($existingEditor==null){
                    throw new Exception('L\'éditeur est introuvable.');
                }
            }

            // check if all authors exist
            $allExist = true;
            $existingAuthors = Author::whereIn('Id', $authors)->get();
            $existingIds = $existingAuthors->map(function($item, $key){
                return $item->Id;
            });

            foreach($authors as $authorId){
                if(!$existingIds->contains($authorId)){
                    $allExist = false;
                }
            }

            if(!$allExist){
                throw new Exception('Un ou plusieurs auteurs n\' existent pas.');
            }

            // Save book
            $book->Isbn = $isbn;
            $book->Title = $title;
            $book->Description = $description;
            $book->Editor_Id = $editorId;

            if($serieId != null){
              $book->Serie_Id = $serieId;
              $book->SerieNumber = $serieNumber;
          }
          else{
              $book->Serie_Id = null;
              $book->SerieNumber = $serieNumber;
          }

            $book->save();

            $book->authors()->detach();
            $book->authors()->attach($authors);
        }
        catch(Exception $e){
          $success = false;
          $error = $e->getMessage();
        }

        return response()->json(new AjaxViewmodel($success,$error, null));
    }

    public function delete(Request $request){
      $success = true;
      $error = '';

      try{
        $book = Book::with('authors')->find($request->input('bookId'));

        if($book == null){
          throw new Exception("Le livre à supprimer n'a pas été trouvé.");
        }

        if($books->authors->count() > 0){
          $book->authors()->detach();
        }
        $book->delete();
      }
      catch(Exception $e){
        $success = false;
        $error = $e->getMessage();
      }

      return response()->json(new AjaxViewmodel($success, $error, null));
    }
}
