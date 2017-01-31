<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::group(['middleware' => 'web'], function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login')->name('doLogin');

    Route::get('register', function(){ return view('auth.register'); })->name('register');
    Route::post('register', 'Auth\RegisterController@register')->name('doRegister');

    // Routes for authenticated users only
    Route::group(['middleware' => 'auth'], function () {
        Route::get('logout', 'Auth\LoginController@logout')->name('logout');

        Route::get('/', 'HomeController');
        Route::get('home', 'HomeController')->name('home');

        Route::get('books', 'BooksController@index')->name('books');
        //Route::get('book-add', 'BooksController@addForm')->name('addBookForm');
        Route::get('book-edit-{id}','BooksController@editForm')->name('editBookForm');
        Route::get('book-add-step-1', function(){ return view('books.add.step1'); })->name('addBookFormStep1');
        Route::post('book-add-step-2', 'BooksController@addForm')->name('addBookFormStep2');

        Route::get('authors', 'AuthorsController@index')->name('authors');
        Route::get('editors', 'EditorsController@index')->name('editors');
        Route::get('series', 'SeriesController@index')->name('series');

        Route::get('loans', 'LoansController@index')->name('loans');
        Route::get('loan-add', 'LoansController@index')->name('createLoanForm');
        Route::get('loan-create', 'LoansController@index')->name('createLoan');

        Route::group(['middleware' => 'ajax'], function(){
          Route::post('book-list', 'BooksController@list')->name('listBooks');
          Route::post('book-create','BooksController@create')->name('addBook');
          Route::post('book-edit','BooksController@update')->name('editBook');
          Route::post('book-delete', 'BooksController@delete')->name('deleteBook');
          Route::post('book-add-step-1-search', 'BooksController@search')->name('searchBook');

          Route::post('author-list', 'AuthorsController@list')->name('listAuthors');
          Route::post('author-add', 'AuthorsController@add')->name('addAuthor');
          Route::post('author-get-modify', 'AuthorsController@getModifyForm')->name('getModifyAuthorForm');
          Route::post('author-modify', 'AuthorsController@modify')->name('modifyAuthor');
          Route::post('author-delete', 'AuthorsController@delete')->name('deleteAuthor');
          Route::get('author-getall-json', 'AuthorsController@getAllJson')->name('getAllAuthorsJson');

          Route::post('editor-list', 'EditorsController@list')->name('listEditors');
          Route::post('editor-add', 'EditorsController@add')->name('addEditor');
          Route::post('editor-get-modify', 'EditorsController@getModifyForm')->name('getModifyEditorForm');
          Route::post('editor-modify', 'EditorsController@modify')->name('modifyEditor');
          Route::post('editor-delete', 'EditorsController@delete')->name('deleteEditor');
          Route::get('editor-getall-json', 'EditorsController@getAllJson')->name('getAllEditorsJson');

          Route::post('serie-list', 'SeriesController@list')->name('listSeries');
          Route::post('serie-add', 'SeriesController@add')->name('addSerie');
          Route::post('serie-get-modify', 'SeriesController@getModifyForm')->name('getModifySerieForm');
          Route::post('serie-modify', 'SeriesController@modify')->name('modifySerie');
          Route::post('serie-delete', 'SeriesController@delete')->name('deleteSerie');
          Route::get('serie-getall-json', 'SeriesController@getAllJson')->name('getAllSeriesJson');
      });
    });
});
