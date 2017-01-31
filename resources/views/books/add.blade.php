@extends('layouts.app')
@section('title', 'Gestion des livres')
@section('h1','<i class="fa fa-book"></i>&nbsp;Ajouter un livre')
@section('breadcrumbs')
  <li><a href="{{route('books')}}">Livres</a></li>
  <li>Ajout</li>
@endsection
@section('scripts')
<script src="{{ URL::asset('js/app/book-add.js') }}"></script>
<script src="{{ URL::asset('js/app/book-isbn.js') }}"></script>
@endsection
@section('content')
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <button type="button" class="closePanelButton btn btn-default btn-xs btn-discrete pull-right"><i class="fa fa-angle-up"></i></button>
      <i class="fa fa-search"></i>&nbsp;Préremplissage
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="form-group col-sm-6">
                <form
                    id="isbn-search-form"
                    data-google-api="https://www.googleapis.com/books/v1/volumes?q=isbn:"
                    data-isbndb-api="{{ route('searchIsbnDB') }}">
                  <label for="isbnSearchField" class="form-label">Chercher un isbn / code barre</label>
                  <div class="input-group">
                    <input type="text" id="isbnSearchField" class="form-control" />
                    <span class="input-group-btn">
                      <button
                        id="btn-search-by-isbn"
                        class="btn btn-default"
                        type="submit"><i class="fa fa-search"></i></button>
                    </span>
                  </div>
                </form>
            </div>

            <div class="form-group col-sm-6">
                <form id="general-search-form">
                  <label for="titleSearchField" class="form-label">Chercher un titre ou un auteur</label>
                  <div class="input-group">
                    <input type="text" id="titleSearchField" class="form-control" disabled="disabled" />
                    <span class="input-group-btn">
                      <button
                        id="btn-title-search"
                        class="btn btn-default"
                        type="submit"
                        data-url="{{route('searchBookByTitle')}}"
                        disabled="disabled" ><i class="fa fa-search"></i></button>
                    </span>
                  </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
<div class="row">
  {!! Form::open([
      'route' => 'addBook',
      'method' => 'post',
      'id' => 'book-form',
      ]) !!}
    <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-pencil"></i>&nbsp;Informations
        </div>
        <div class="panel-body">
          <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('book-title', 'Titre') !!}
                  {!! Form::text('book-title', '', ['class' => 'form-control', 'id' => 'add-book-form-title']) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('book-isbn', 'Isbn') !!}
                  {!! Form::text('book-isbn', '', ['class' => 'form-control', 'id' => 'add-book-form-isbn']) !!}
                </div>

                <div class="form-group">
                  <span>{!! Form::label('book-editor', 'Editeur') !!}
                      <button
                        id='btnUpdateEditors'
                        type="button"
                        data-url="{{route('getAllEditorsJson')}}"
                        class="btn btn-primary btn-xs">
                          <i class="fa fa-repeat"></i>
                      </button>
                  </span>
                  {!! Form::select(
                    'book-editor-id',
                    $viewmodel->editors->sortBy('Name')->pluck('Name', 'Id'),
                    '',
                    ['class' => 'form-control',
                     'id'=>'add-book-form-editorId',
                     'placeholder' => '']) !!}
                  <div class="alert alert-info hide" id="alert-editor-not-found">
                    L'éditeur suivant n'a pas été trouvé dans la liste. Veuillez le créer ou sélectionner un éditeur existant.
                    <br />
                    <i><span id="editor-not-found-name"></span></i>
                    <button
                      id="btn-add-new-editor"
                      class="btn btn-default btn-xs"
                      type="button"
                      title="Créer l'éditeur"
                      data-url="{{ route('addEditor') }}"><i class="fa fa-plus"></i></button>
                  </div>
                </div>

                <div class="form-group">
                  <span>{!! Form::label('book-from-serie', 'Série') !!}
                    <button
                      id='btnUpdateSeries'
                      type="button"
                      data-url="{{route('getAllSeriesJson')}}"
                      class="btn btn-primary btn-xs">
                        <i class="fa fa-repeat"></i>
                    </button>
                  </span>
                  <div class="row">
                    <div class="col-xs-12">
                      {!! Form::select(
                        'book-serie-id',
                        $viewmodel->series->sortBy('Title')->pluck('Title', 'Id'),
                        '',
                        ['class' => 'form-control',
                         'id'=>'add-book-form-serieId',
                         'placeholder' => 'Aucune']) !!}
                     </div>
                    <div class="col-xs-12 col-sm-6">
                      <div class="numberInputContainer input-group">
                        <span class="input-group-btn">
                          <button
                            id="btnSerieNumberSubstract"
                            class="numberInputSubstract btn btn-default"
                            type="button"
                            data-min="0"
                            disabled="disabled">
                            <i class="fa fa-minus"></i>
                          </button>
                        </span>
                        {!! Form::text('book-serie-number', '', ['class' => 'form-control numberInput', 'id' => 'add-book-form-serieNumber', 'disabled' => 'disabled']) !!}
                        <span class="input-group-btn">
                          <button
                            id="btnSerieNumberAdd"
                            class="numberInputAdd btn btn-default"
                            type="button"
                            disabled="disabled">
                            <i class="fa fa-plus"></i>
                          </button>
                        </span>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('book-authors', 'Auteur(s)') !!}
                <button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#add-author-modal">
                  <i class="fa fa-plus"></i>
                </button>
                <ul id="book-authors-list">

                </ul>
                <div
                  id="alert-author-not-found"
                  class="alert alert-info hide"
                  data-url="{{ route('addAuthor') }}">
                  Le ou les auteurs suivant n'ont pas été trouvés dans la liste :
                  <ul id="authors-not-found-list">
                    <li id="author-not-found-template" class="hide">
                        <span class="author-not-found-name"></span>
                        <button
                            class="add-author-not-found-button btn btn-xs btn-primary"
                            type="button"
                            title="Créer et sélectionner l'auteur">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button
                          class="hide-author-not-found-button btn btn-xs btn-danger"
                          type="button"
                          title="Cacher cet auteur">
                          <i class="fa fa-times"></i>
                        </button>
                    </li>
                  </ul>
                  Veuillez les créer ou sélectionner des auteurs existant.
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('book-description', 'Description') !!}
                {!! Form::textarea('book-description', '', ['class' => 'form-control', 'id' => 'add-book-form-description']) !!}
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <div class="pull-right">
          <a href="{{route('books')}}" class="btn btn-primary" id="btnGoBack">Annuler</a>
          {!! Form::submit('Ajouter', ['class' => 'btn btn-success', 'id' => 'add-book-form-submit']) !!}
        </div>
          <div class="clearfix"></div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<div id="search-result-template" class="hide">
  @include('books.search-results')
</div>

<!-- Add author modal -->
<div
  id="add-author-modal"
  class="modal fade"
  tabindex="-1"
  role="dialog">
  <div
    class="modal-dialog modal-lg"
    role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-user"></i>&nbsp;Ajouter un auteur</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <select id="add-book-form-authorId" class="form-control">
            @foreach($viewmodel->authors->sortBy('Name') as $author)
            <option value='{{$author->Id}}'>{{$author->Name}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button
          id="btnUpdateAuthors"
          type="button"
          class="btn btn-primary pull-left"
          data-url="{{ route('getAllAuthorsJson') }}"><i class="fa fa-refresh"></i> Actualiser</button>
        <button class="btn btn-primary" data-dismiss="modal" aria-label="Close">Annuler</button>
        <button class="btn btn-success" id="btn-add-author"><i class="fa fa-plus"></i> Ajouter</button>
      </div>
    </div>
  </div>
</div>

<!-- ISBN modal -->
<div
    id="isbn-search-modal"
    class="modal fade"
    tabindex="-1"
    role="dialog">
    <div
        class="modal-dialog modal-lg"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-search"></i>&nbsp;Recherche par ISBN</h4>
            </div>
            <div class="modal-body">
                @include('books.isbn-search-results')
            </div>
            <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-primary"
                  data-dismiss="modal">Close</button>
                  <button
                    id="btnConfirmIsbnSearchResult"
                    type="button"
                    class="btn btn-success">
                    <i class="fa fa-check"></i>&nbsp;Valider
                  </button>
            </div>
        </div>
    </div>
</div>

<!-- Search modal -->
<div
  id="search-results-modal"
  class="modal fade"
  tabindex="-1"
  role="dialog">
  <div
    class="modal-dialog modal-lg"
    role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-book"></i>&nbsp;Résultats de la recherche</h4>
      </div>
      <div class="modal-body">
        <div class="row" id="search-results-list">
        </div>
        <div class="clearfix" />
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-default"
          data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection
