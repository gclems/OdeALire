<?php
namespace App\Viewmodels\Editors;
use App;
use App\Viewmodels\PaginationViewmodel;

class EditorIndexListViewmodel{
  public $editors;
  public $paginationViewmodel;

    function __construct() {
      $this->paginationViewmodel = new PaginationViewmodel();
      $this->editors = [];
    }
}
