@extends('layouts.app')
@section('title', 'Gestion des livres')
@section('h1','<i class="fa fa-book"></i>&nbsp;Gestion des livres')
@section('breadcrumbs')
  <li>Livres</li>
@endsection
@section('scripts')
<script src="{{ URL::asset('js/app/book.js') }}"></script>
@endsection
@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <a
      href="{{route('addBookFormStep1')}}"
      class="btn btn-success pull-right">
      <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;Nouveau</span>
    </a>
    Liste des livres
  </div>

  <div class="panel-body">
    <div class="m-b-1">
      {!! Form::open([
          'route' => 'listBooks',
          'method' => 'post',
          'id' => 'filters-form'
          ]) !!}
        <div class="form-inline">
          <div class="form-group">
            <label for="maxNumber">En afficher</label>
            {!! Form::select('maxNumber', ['10' => '10', '50' => '50', '100'=>'100'], '50', ['class' => 'form-control', 'id'=>'filter-per-page-number']) !!}
          </div>
          <div class="form-group">
            <label for="">Triés par</label>
            {!! Form::select('orderByColumn', ['CreatedAt' => 'Date d\'ajout', 'Title' => 'Titre'], 'CreatedAt', ['class' => 'form-control', 'id'=>'filter-order-by']) !!}
            {!! Form::select('orderByDirection', ['asc' => 'Croissant', 'desc' => 'Décroissant'], 'desc', ['class' => 'form-control', 'id'=>'filter-order-by-direction']) !!}
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-search"></i></div>
                {!! Form::text('search', '', ['class' => 'form-control', 'id' => 'search-book-field', 'placeholder' => 'Rechercher par titre']) !!}
                <div class="input-group-addon" role="button" id="reset-search-button">
                  <i class="fa fa-times"></i>
                </div>
            </div>
          </div>
          {!! Form::submit('Appliquer', ['class' => 'btn btn-primary', 'id' => 'apply-filter-button']) !!}
        </div>
      {!! Form::close() !!}
    </div>

    <div
        id="books-list-container"
        data-delete-url="{{ route('deleteBook') }}">
        @include('books.index-books-list', ['viewmodel'=>$viewmodel])
    </div>
  </div>
</div>
@endsection
