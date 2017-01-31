<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Viewmodels\HomeViewmodel;
use App\Book;
use App\Author;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(){
        $viewmodel = new HomeViewmodel();
        $viewmodel->totalBooks = Book::count();
        $viewmodel->totalLents = 0;
        $viewmodel->lentCount = 3;

        if($viewmodel->totalBooks == 0 || $viewmodel->lentCount == 0){
            $viewmodel->lentPercent = 0;
        }
        else{
            $viewmodel->lentPercent = 100 * $viewmodel->lentCount / $viewmodel->totalBooks;
        }

        $viewmodel->latestBooks = Book::orderBy('CreatedAt', 'desc')
                                      ->take(10)
                                      ->get();
        $viewmodel->latestAuthors = Author::orderBy('CreatedAt', 'desc')
                                          ->take(10)
                                          ->get();;
        $viewmodel->currentLents = [];

        return view('home', ['viewmodel' => $viewmodel]);
    }
}
