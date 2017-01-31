@extends('layouts.app')
@section('title', 'Accueil')
@section('content')
<div class="row tile_count">
    <div class="col-lg-2 col-lg-offset-1 col-md-2 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-4 col-xs-offset-0 tile_stats_count">
        <span class="count_top"><i class="fa fa-book"></i>&nbsp;Livres</span>
        <div class="count">{{ $viewmodel->totalBooks }}</div>
    </div>

    <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-4 col-sm-offset-0 col-xs-4 col-xs-offset-0 tile_stats_count">
        <span class="count_top"><i class="fa fa-exchange"></i>&nbsp;Prêts</span>
        <div class="count">{{ $viewmodel->totalLents }}</div>
    </div>

    <div class="col-lg-2 col-lg-offset-1 col-md-2 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-4 col-xs-offset-0 tile_stats_count">
        <span class="count_top"><i class="fa fa-exchange"></i>&nbsp;Prêts en cours</span>
        <div class="count">{{ $viewmodel->lentCount }}</div>
        <span class="count_bottom">
            <i class="green">{{ number_format($viewmodel->lentPercent,2) }}%</i>
        </span>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 text-center">
        <a href="{{ route('books')}}" class="btn btn-success btn-lg"><i class="fa fa-exchange"></i>&nbsp;Prêter un livre</a>
    </div>
</div>

<div class="panel panel-default m-t-1">
  <div class="panel-heading">
    <button type="button" class="closePanelButton btn btn-default btn-xs btn-discrete pull-right"><i class="fa fa-angle-up"></i></button>
    <i class="fa fa-exchange"></i>&nbsp;Prêts en cours
  </div>
  <div class="panel-body">
        <div class="row">
            <!--<table class="table table-responsive table-striped table-hover">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Emprunteur</th>
                        <th>Emprunté le</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>-->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <button type="button" class="closePanelButton btn btn-default btn-xs btn-discrete pull-right"><i class="fa fa-angle-up"></i></button>
            <i class="fa fa-book"></i>&nbsp;Derniers livres ajoutés
          </div>
          <div class="panel-body">
              <div class="row">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date d'ajout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($viewmodel->latestBooks as $book)
                        <tr>
                            <td>{{ $book->Title }}</td>
                            <td>{{ $book->CreatedAt->format(Config::get('constants.dateTimeFormat')) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <button type="button" class="closePanelButton btn btn-default btn-xs btn-discrete pull-right"><i class="fa fa-angle-up"></i></button>
            <i class="fa fa-user"></i>&nbsp;Derniers auteurs ajoutés
          </div>
          <div class="panel-body">
              <div class="row">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Date d'ajout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($viewmodel->latestAuthors as $author)
                        <tr>
                            <td>{{ $author->Name }}</td>
                            <td>{{ $author->CreatedAt->format(Config::get('constants.dateTimeFormat')) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
