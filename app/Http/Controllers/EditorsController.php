<?php
namespace App\Http\Controllers;
use Exception;
use Auth;
use App\Editor;
use App\Viewmodels\AjaxViewmodel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Viewmodels\Editors\EditorIndexListViewmodel;
use App\Viewmodels\PaginationViewmodel;

class EditorsController extends Controller{

  public function index(){
    return view('editors.index', ['viewmodel'=>$this->listEditors(50, 1, 'CreatedAt', 'desc', null)]);
  }

  public function list(Request $request){
      $success = false;
      $error = null;
      $view=null;

      try{
        $viewmodel = $this->listEditors(
           $request->input('maxNumber'),
           $request->input('pageNumber'),
           $request->input('orderByColumn'),
           $request->input('orderByDirection'),
           $request->input('search'));
        $view = view('editors.index-editors-list', ['viewmodel' => $viewmodel])->render();
        $success = true;
      }
      catch(Exception $e){
          $error = $e->getMessage();
      }

      return response()->json(new AjaxViewmodel($success, $error, $view));
  }

  private function listEditors(
      $takeMax,
      $pageNumber,
      $orderByColumn,
      $orderByDirection,
      $searchedName){

      $viewmodel = new EditorIndexListViewmodel();
      $viewmodel->editors = Editor::withCount('books')->with('creator');

      if((isset($searchedName) && trim($searchedName) !=='')){
          $viewmodel->editors = $viewmodel->editors->where('Name', 'like', '%' . $searchedName . '%');
      }

      $count = $viewmodel->editors->count();

      $viewmodel->editors = $viewmodel->editors->orderBy($orderByColumn, $orderByDirection)
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
          $editors = Editor::orderBy('Name', 'asc')->get();
          foreach($editors as $editor){
              array_push($array, ['id' => $editor->Id, 'name'=> $editor->Name]);
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
      $editorName = $request->input('editor-name');

      $existingEditors = Editor::where('Name', $editorName)->get();
      if (!$existingEditors->isEmpty()){
        throw new Exception('Un éditeur identique existe déjà.');
      }

      if($this->IsNullOrEmptyString($editorName)){
          throw new Exception('Un éditeur doit avoir un nom.');
      }

      $editor = new Editor;
      $editor->Name = $editorName;
      $editor->Creator_Id = Auth::user()->Id;
      $editor->save();

      $view = ["id" => $editor->Id, "name" => $editor->Name];
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
      $editorId = $request->input('editorId');
      $editor = Editor::Find($editorId);
      if($editor==null){
        throw new Exception('Impossible de générer un formulaire de modification pour un éditeur introuvable.');
      }

      $view = view('editors.index-editors-modify-dialog', ['editor' => $editor])->render();
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
      $editorId = $request->input('modify-editor-id');
      $editorName = $request->input('modify-editor-name');

      $editor = Editor::Find($editorId);
      if ($editor==null){
        throw new Exception('L\'éditeur à modifier est introuvable.');
      }

      if($this->IsNullOrEmptyString($editorName)){
          throw new Exception('Un éditeur doit avoir un nom.');
      }

      $existingEditors = Editor::where('Name', $editorName)
                               ->where('Id', '<>', $editorId)
                               ->get();

      if (!$existingEditors->isEmpty()){
        throw new Exception('Un éditeur identique existe déjà.');
      }

      $editor->Name = $editorName;
      $editor->save();

      $view = ["id" => $editor->Id, "name" => $editor->Name];
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
      $editor = Editor::withCount('books')->find($request->input('editorId'));

      if($editor->books_count > 0){
          throw new Exception("Impossible de supprimer un éditeur dont des livres sont enregistrés.");
      }

      $editor->delete();
    }
    catch(Exception $e){
      $success = false;
      $error = $e->getMessage();
    }

    return response()->json(new AjaxViewmodel($success, $error, null));
  }
}
