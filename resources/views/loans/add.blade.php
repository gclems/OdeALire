@extends('layouts.app')
@section('title', 'Prêter un livre')
@section('h1','<i class="fa fa-exchange"></i>&nbsp;Prêter un livre')
@section('breadcrumbs')
  <li>Prêter un livre</li>
@endsection
@section('scripts')

@endsection
@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    Création du prêt
  </div>
  <div class="panel-body">
    {!! Form::open([
        'route' => 'listSeries',
        'method' => 'post',
        'id' => 'filters-form'
        ]) !!}

    {!! Form::close() !!}
  </div>
</div>
@endsection
