<?php
namespace App\Http\Controllers;
use Exception;
use Auth;
use App\Serie;
use App\Viewmodels\AjaxViewmodel;
use App\Http\Controllers\Controller;
use App\Viewmodels\Series\SerieIndexListViewmodel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeriesController extends Controller{

  public function index(){
    return view('series.index', ['viewmodel'=>$this->listSeries(50, 1, 'CreatedAt', 'desc', null)]);
  }

  public function list(Request $request){
      $success = false;
      $error = null;
      $view=null;

      try{
        $viewmodel = $this->listSeries(
           $request->input('maxNumber'),
           $request->input('pageNumber'),
           $request->input('orderByColumn'),
           $request->input('orderByDirection'),
           $request->input('search'));
        $view = view('series.index-series-list', ['viewmodel' => $viewmodel])->render();
        $success = true;
      }
      catch(Exception $e){
          $error = $e->getMessage();
      }

      return response()->json(new AjaxViewmodel($success, $error, $view));
  }

  private function listSeries(
      $takeMax,
      $pageNumber,
      $orderByColumn,
      $orderByDirection,
      $searchedName){

      $viewmodel = new SerieIndexListViewmodel();
      $viewmodel->series = Serie::withCount('books')->with('creator');

      if((isset($searchedName) && trim($searchedName) !=='')){
          $viewmodel->series = $viewmodel->series->where('Title', 'like', '%' . $searchedName . '%');
      }

      $count = $viewmodel->series->count();

      $viewmodel->series = $viewmodel->series->orderBy($orderByColumn, $orderByDirection)
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
    $array=[];

    try{
      $series = Serie::orderBy('Title', 'asc')->get();
      foreach($series as $serie){
          array_push($array, ['id' => $serie->Id, 'title'=> $serie->Title]);
      }
    }
    catch(Exception $e){
      $success = false;
      $error = $e->getMessage();
    }

    return response()->json(new AjaxViewmodel($success, $error, $array));
  }

  public function add(Request $request){
    $success = true;
    $error = '';
    $view=null;

    try{
      $serieName = $request->input('serie-name');

      if($this->IsNullOrEmptyString($serieName)){
          throw new Exception('Une série doit avoir un titre.');
      }

      $existingSeries = Serie::where('Title', $serieName)->get();
      if (!$existingSeries->isEmpty()){
        throw new Exception('Une série identique existe déjà.');
      }

      $serie = new Serie;
      $serie->Title = $serieName;
      $serie->Creator_Id = Auth::user()->Id;
      $serie->save();

      $view = ["id" => $serie->Id, "title" => $serie->Title];
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
      $serieId = $request->input('serieId');
      $serie = Serie::Find($serieId);
      if($serie==null){
        throw new Exception('Impossible de générer un formulaire de modification pour une série introuvable.');
      }

      $view = view('series.index-series-modify-dialog', ['serie' => $serie])->render();
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
      $serieId = $request->input('modify-serie-id');
      $serieName = $request->input('modify-serie-name');

      $serie = Serie::Find($serieId);
      if ($serie==null){
        throw new Exception('La série à modifier est introuvable.');
      }

      if($this->IsNullOrEmptyString($serieName)){
          throw new Exception('Une série doit avoir un titre.');
      }

      $existingSeries = Serie::where('Title', $serieName)
                             ->where('Id', '<>', $serieId)
                             ->get();

      if (!$existingSeries->isEmpty()){
        throw new Exception('Une série identique existe déjà.');
      }

      $serie->Title = $serieName;
      $serie->save();

      $view = ["id" => $serie->Id, "title" => $serie->Title];
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
      $serie = Serie::withCount('books')->find($request->input('serieId'));

      if($serie->books_count > 0){
          throw new Exception("Impossible de supprimer une série dont des livres sont enregistrés.");
      }

      $serie->delete();
    }
    catch(Exception $e){
      $success = false;
      $error = $e->getMessage();
    }

    return response()->json(new AjaxViewmodel($success, $error, null));
  }
}
