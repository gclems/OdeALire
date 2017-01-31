<?php
namespace App\Viewmodels;

  class PaginationViewmodel{
    public $totalCount = 0;
    public $currentPageNumber = 1;
    public $countPerPage = 1;

    public function getNumberOfPages(){
      return ceil($this->totalCount / $this->countPerPage);
    }
  }
