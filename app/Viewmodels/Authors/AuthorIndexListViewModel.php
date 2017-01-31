<?php
namespace App\Viewmodels\Authors;
use App;
use App\Viewmodels\PaginationViewmodel;

class AuthorIndexListViewmodel{
  public $authors;
  public $paginationViewmodel;

    function __construct() {
      $this->paginationViewmodel = new PaginationViewmodel();
      $this->authors = [];
    }
}
