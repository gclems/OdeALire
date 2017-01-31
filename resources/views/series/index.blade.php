@extends('layouts.app')
@section('title', 'Gestion des séries')
@section('h1','<i class="fa fa-tags"></i>&nbsp;Gestion des séries')
@section('breadcrumbs')
  <li>Séries</li>
@endsection
@section('scripts')
<script src="{{ URL::asset('js/app/serie.js') }}"></script>
@endsection
@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <button
      type="button"
      class="btn btn-success pull-right"
      data-toggle="modal"
      data-target="#add-serie-modal">
      <i class="fa fa-plus"></i>&nbsp;Nouvelle
    </button>
    Liste des séries
  </div>
  <div class="panel-body">
    <div class="m-b-1">
      {!! Form::open([
          'route' => 'listSeries',
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
                {!! Form::text('search', '', ['class' => 'form-control', 'id' => 'search-serie-field', 'placeholder' => 'Rechercher par titre']) !!}
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
        id="series-list-container"
        data-delete-url="{{ route('deleteSerie') }}"
        data-modify-url="{{ route('getModifySerieForm') }}">
        @include('series.index-series-list', ['viewmodel'=>$viewmodel])
    </div>
  </div>
</div>

@include('series.index-series-add-dialog', ['viewmodel'=>$viewmodel])
<div id="modify-form-modal-container"></div>
@endsection
