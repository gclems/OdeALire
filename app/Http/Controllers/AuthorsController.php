<?php
namespace App\Http\Controllers;
use Exception;
use Auth;
use App\Author;
use App\Viewmodels\AjaxViewmodel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Viewmodels\Authors\AuthorIndexListViewmodel;
use App\Viewmodels\PaginationViewmodel;

class AuthorsController extends Controller{

    public function index(){
      return view('authors.index', ['viewmodel'=>$this->listAuthors(50, 1, 'CreatedAt', 'desc', null)]);
    }

    public function list(Request $request){
        $success = false;
        $error = null;
        $viewmodel=null;
        try{
          $viewmodel = $this->listAuthors(
             $request->input('maxNumber'),
             $request->input('pageNumber'),
             $request->input('orderByColumn'),
             $request->input('orderByDirection'),
             $request->input('search'));

            $success = true;
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $viewmodel = new AuthorIndexListViewmodel();
        }

        return response()->json(new AjaxViewmodel(
            $success,
            $error,
            ($success ? view('authors.index-authors-list', ['viewmodel' => $viewmodel])->render() : null)));
    }

    private function listAuthors(
        $takeMax,
        $pageNumber,
        $orderByColumn,
        $orderByDirection,
        $searchedName){

        $viewmodel = new AuthorIndexListViewmodel();
        $viewmodel->authors = Author::withCount('books')->with('creator');

        if((isset($searchedName) && trim($searchedName) !=='')){
            $viewmodel->authors =$viewmodel->authors->where('Name', 'like', '%' . $searchedName . '%');
        }

        $count = $viewmodel->authors->count();

        $viewmodel->authors =$viewmodel->authors->orderBy($orderByColumn, $orderByDirection)
                                                ->skip(max(0, $pageNumber - 1) * $takeMax)
                                                ->take($takeMax)
                                                ->get();

        $viewmodel->paginationViewmodel->totalCount = $count;
        $viewmodel->paginationViewmodel->currentPageNumber = $pageNumber;
        $viewmodel->paginationViewmodel->countPerPage = $takeMax;

        return $viewmodel;
    }

    public function getAllJson(){
      $success = true;
      $error = '';
      $array = [];

      try{
          $autors = Author::orderBy('Name', 'asc')->get();
          foreach($autors as $author){
              array_push($array, ['id' => $author->Id, 'name'=> $author->Name]);
          }
        }
        catch(Exception $e){
            $success = false;
            $error = $e->getMessage();
        }
      return response()->json(new AjaxViewmodel($success,$error, $array));
    }

    public function add(Request $request){
      $success = true;
      $error = '';
      $view = null;

      try{
        $authorName = $request->input('author-name');

        if($this->IsNullOrEmptyString($authorName)){
            throw new Exception('Un auteur doit avoir un nom.');
        }

        $existingAuthors = Author::where('Name', $authorName)->get();
        if (!$existingAuthors->isEmpty()){
          throw new Exception('Un auteur identique existe déjà.');
        }

        $author = new Author;
        $author->Name = $authorName;
        $author->Creator_Id = Auth::user()->Id;
        $author->save();

        $view = ['id' => $author->Id , 'name' => $author->Name];
      }
      catch(Exception $e){
          $success = false;
          $error = $e->getMessage();
      }

      return response()->json(new AjaxViewmodel($success,$error, $view));
    }

    public function getModifyForm(Request $request){
      $success = true;
      $error = '';
      $view = null;

      try{
        $authorId = $request->input('authorId');
        $author = Author::Find($authorId);
        if($author==null){
          throw new Exception('Impossible de générer un formulaire de modification pour un auteur introuvable.');
        }

        $view = view('authors.index-authors-modify-dialog', ['author' => $author])->render();
      }
      catch(Exception $e){
        $success = false;
        $error = $e->getMessage();
        $view = null;
      }

      return response()->json(new AjaxViewmodel($success, $error, $view));
    }

    public function modify(Request $request){
      $success = true;
      $error = '';
      $view=null;

      try{
        $authorId = $request->input('modify-author-id');
        $authorName = $request->input('modify-author-name');

        $author = Author::Find($authorId);
        if ($author==null){
          throw new Exception('L\'auteur à modifier est introuvable.');
        }

        if($this->IsNullOrEmptyString($authorName)){
            throw new Exception('Un auteur doit avoir un nom.');
        }

        $existingAuthors = Author::where('Name', $authorName)
                                 ->where('Id', '<>', $authorId)
                                 ->get();

        if (!$existingAuthors->isEmpty()){
          throw new Exception('Un auteur identique existe déjà.');
        }

        $author->Name = $authorName;
        $author->save();

        $view = ['id' => $author->Id , 'name' => $author->Name];
      }
      catch(Exception $e){
          $success = false;
          $error = $e->getMessage();
      }

      return response()->json(new AjaxViewmodel($success, $error, $view));
    }

    public function delete(Request $request){
      $success = true;
      $error = '';

      try{
        $author = Author::withCount('books')->find($request->input('authorId'));

        if($author == null){
          throw new Exception("L'auteur à supprimer n'a pas été trouvé.");
        }

        if($author->books_count > 0){
            throw new Exception("Impossible de supprimer un auteur dont des livres sont enregistrés.");
        }

        $author->delete();
      }
      catch(Exception $e){
        $success = false;
        $error = $e->getMessage();
      }

      return response()->json(new AjaxViewmodel($success, $error, null));
    }
}
