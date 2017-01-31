<?php
namespace App\Viewmodels\Series;
use App;
use App\Viewmodels\PaginationViewmodel;

class SerieIndexListViewmodel{
  public $series;
  public $paginationViewmodel;

    function __construct() {
      $this->paginationViewmodel = new PaginationViewmodel();
      $this->series = [];
    }
}
