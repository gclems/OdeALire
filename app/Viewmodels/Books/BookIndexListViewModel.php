<?php
namespace App\Viewmodels\Books;
use App;
use App\Viewmodels\PaginationViewmodel;

class BookIndexListViewmodel{
  public $books;
  public $paginationViewmodel;

    function __construct() {
      $this->paginationViewmodel = new PaginationViewmodel();
      $this->books = [];
    }
}
