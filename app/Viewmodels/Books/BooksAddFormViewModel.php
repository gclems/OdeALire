<?php
namespace App\Viewmodels\Books;
use App;

class BooksAddFormViewModel{
    public $authors;
    public $editors;
    public $series;

    public $title = null;
    public $isbn = null;
    public $description = null;
    public $publisherId = null;
    public $bookAuthors = array();


    public $unknownAuthorsNames = array();
    public $unknownPublisherName = null;
}
