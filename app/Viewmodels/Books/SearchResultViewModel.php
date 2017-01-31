<?php
namespace App\Viewmodels\Books;
use App;
use App\Viewmodels\PaginationViewmodel;

class SearchResultViewModel{
  public $source = null;

  public $isbn = null;
  public $title = null;

  public $editorName = null;
  public $authorsNames = array();
  public $description = null;
}
