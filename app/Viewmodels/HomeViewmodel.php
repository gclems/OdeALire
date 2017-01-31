<?php
namespace App\Viewmodels;

  class HomeViewmodel{
    public $lentCount = 0;
    public $totalLents = 0;
    public $totalBooks = 0;
    public $totalAuthors = 0;
    public $lentPercent = 0;

    public $latestBooks = [];
    public $latestAuthors = [];
    public $currentLents = [];
  }
