@extends('layouts.app')
@section('title', 'Gestion des auteurs')
@section('h1','<i class="fa fa-user"></i>&nbsp;Gestion des auteurs')
@section('breadcrumbs')
  <li>Auteurs</li>
@endsection
@section('scripts')
<script src="{{ URL::asset('js/app/author.js') }}"></script>
@endsection
@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <button
      type="button"
      class="btn btn-success pull-right"
      data-toggle="modal"
      data-target="#add-author-modal">
      <i class="fa fa-plus"></i>&nbsp;Nouveau
    </button>
    Liste des auteurs
  </div>

  <div class="panel-body">
    <div class="m-b-1">
      {!! Form::open([
          'route' => 'listAuthors',
          'method' => 'post',
          'id' => 'filters-form'
          ]) !!}
        <div class="form-inline">
          <div class="form-group">
            <label for="">En afficher</label>
            {!! Form::select('maxNumber', ['10' => '10', '50' => '50', '100'=>'100'], '50', ['class' => 'form-control', 'id'=>'filter-per-page-number']) !!}
          </div>
          <div class="form-group">
            <label for="">Triés par</label>
            {!! Form::select('orderByColumn', ['CreatedAt' => 'Date d\'ajout', 'Name' => 'Nom'], 'CreatedAt', ['class' => 'form-control', 'id'=>'filter-order-by']) !!}
            {!! Form::select('orderByDirection', ['asc' => 'Croissant', 'desc' => 'Décroissant'], 'desc', ['class' => 'form-control', 'id'=>'filter-order-by-direction']) !!}
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-search"></i></div>
                {!! Form::text('search', '', ['class' => 'form-control', 'id' => 'search-author-field', 'placeholder' => 'Rechercher par nom']) !!}
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
        id="authors-list-container"
        data-delete-url="{{ route('deleteAuthor') }}"
        data-modify-url="{{ route('getModifyAuthorForm') }}">
        @include('authors.index-authors-list', ['viewmodel'=>$viewmodel])
    </div>
  </div>
</div>

@include('authors.index-authors-add-dialog', ['viewmodel'=>$viewmodel])
<div id="modify-form-modal-container"></div>
@endsection
