@extends('layouts.app')
@section('title', 'Gestion des livres')
@section('h1','<i class="fa fa-book"></i>&nbsp;Ajouter un livre')
@section('breadcrumbs')
  <li><a href="{{route('books')}}">Livres</a></li>
  <li>Ajouter un livre</li>
@endsection
@section('scripts')
  <script src="{{ URL::asset('js/app/books/add.step1.js') }}"></script>
@endsection
@section('content')
    @include('books.add.wizard', ['step'=>1])

    <div class="panel panel-default m-t-1">
      <div class="panel-heading">
        <i class="fa fa-search"></i>&nbsp;Rechercher
      </div>
        <div class="panel-body">
          {!! Form::open([
              'route' => 'searchBook',
              'method' => 'post',
              'id' => 'search-book-form']) !!}
            <div class="row">
              <div class="col-xs-12 col-sm-5">
                <div class="form-group">
                  {!! Form::label('book-isbn', 'ISBN') !!}
                  {!! Form::text('book-isbn', '', ['class' => 'form-control', 'id' => 'search-book-form-isbn']) !!}
                </div>
              </div>
              <div class="col-xs-12 col-sm-2 form-separator">
                <span>ou</span>
              </div>
              <div class="col-xs-12 col-sm-5">
                <div class="form-group">
                  {!! Form::label('book-title', 'Titre/Auteur') !!}
                  {!! Form::text('book-title', '', ['class' => 'form-control', 'id' => 'search-book-form-title']) !!}
                </div>
              </div>
            </div>

            {!! Form::submit('Rechercher', ['class' => 'btn btn-primary center-block m-t-1']) !!}

          {!! Form::close() !!}
        </div>
  </div>

  <div class="panel panel-default m-t-1 hide" id="search-results-panel">
    <div class="panel-heading">
      <i class="fa fa-list"></i>&nbsp;Résultats & sélection
    </div>
    <div class="panel-body" id="search-results-body">
    </div>
  </div>

  {!! Form::open([
      'route' => 'addBookFormStep2',
      'method' => 'post',
      'id' => 'select-book-form']) !!}
      {!! Form::hidden('isbn', '', ['id' => 'select-book-isbn']) !!}
      {!! Form::hidden('title','', ['id' => 'select-book-title']) !!}
      {!! Form::hidden('publisher','', ['id' => 'select-book-publisher']) !!}
      {!! Form::hidden('authors','', ['id' => 'select-book-authors']) !!}
      {!! Form::hidden('description','', ['id' => 'select-book-description']) !!}
  {!! Form::close() !!}
  <button
    type="button"
    id="btn-go-to-step-2"
    class="pull-right m-b-1 btn btn-default">
    Continuer sans sélection >>
  </button>
@endsection
