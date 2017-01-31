<?php
namespace App\Http\Controllers;
use Exception;
use Auth;
use App\Loan;
use App\Viewmodels\AjaxViewmodel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Viewmodels\PaginationViewmodel;

class LoansController extends Controller{

  public function index(){
    return view('loans.index', ['viewmodel'=>null]);
  }

  public function create(Request $request){
    return view('loans.index', ['viewmodel'=>null]);
  }
}
