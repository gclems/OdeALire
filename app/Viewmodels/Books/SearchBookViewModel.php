<?php
namespace App\Viewmodels\Books;
use App;
use App\Viewmodels\PaginationViewmodel;

class SearchBookViewModel{
  public $googleSearchState = true;
  public $isbnDBSearchState = true;
  public $worldCatSearchState = true;

  public $googleError = null;
  public $isbnDBError = null;
  public $worldCatError = null;

  public $searchResults = array();
}
