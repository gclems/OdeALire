@extends('layouts.app')
@section('title', 'Gestion des livres')
@section('h1','<i class="fa fa-book"></i>&nbsp;Ajouter un livre')
@section('breadcrumbs')
  <li><a href="{{route('books')}}">Livres</a></li>
  <li>Ajouter un livre</li>
@endsection
@section('scripts')
  <script src="{{ URL::asset('js/app/books/add.step2.js') }}"></script>
@endsection
@section('content')
  @include('books.add.wizard', ['step'=>2])
  {!! Form::open([
      'route' => 'addBook',
      'method' => 'post',
      'id' => 'book-form',
      ]) !!}
  <div class="panel panel-default m-t-1">
    <div class="panel-heading">
      <i class="fa fa-pencil"></i>&nbsp;Données du livre
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-6">
          <!-- Title -->
          <div class="form-group">
            {!! Form::label('book-title', 'Titre') !!}
            {!! Form::text('book-title', $viewmodel->title , ['class' => 'form-control', 'id' => 'add-book-form-title']) !!}
          </div>

          <!-- Isbn -->
          <div class="form-group">
            {!! Form::label('book-isbn', 'Isbn') !!}
            {!! Form::text('book-isbn', $viewmodel->isbn, ['class' => 'form-control', 'id' => 'add-book-form-isbn']) !!}
          </div>

          <!-- Publisher -->
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
              ($viewmodel->publisherId != null ? $viewmodel->publisherId : ''),
              ['class' => 'form-control',
               'id'=>'add-book-form-editorId',
               'placeholder' => '']) !!}
            @if(isset($viewmodel->unknownPublisherName) && $viewmodel->unknownPublisherName != '')
            <div class="alert alert-info" id="alert-editor-not-found">
              L'éditeur suivant n'a pas été trouvé dans la liste. Veuillez le créer ou sélectionner un éditeur existant.
              <br />
              <i><span id="editor-not-found-name">{{ $viewmodel->unknownPublisherName }}</span></i>
              <button
                id="btn-add-new-editor"
                class="btn btn-primary btn-xs"
                type="button"
                title="Créer l'éditeur"
                data-url="{{ route('addEditor') }}">
                <i class="fa fa-plus"></i>
              </button>
              <button
                type="button"
                id="btn-publisher-name-remove"
                title="Cacher la suggestion"
                class="btn btn-xs btn-danger">
                <i class="fa fa-times"></i>
              </button>
            </div>
            @endif
          </div>

          <!-- Serie -->
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
          <!-- Authors -->
          <div class="form-group">
            {!! Form::label('book-authors', 'Auteur(s)') !!}
            <button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#add-author-modal">
              <i class="fa fa-plus"></i>
            </button>
            <ul id="book-authors-list">
              @foreach($viewmodel->bookAuthors as $author)
                <li data-id="{{ $author->Id }}">
                  {{ $author->Name }}
                  <button
                    type="button"
                    class="btn btn-xs btn-danger btn-remove-author">
                    <i class="fa fa-times"></i>
                  </button>
                </li>
              @endforeach
            </ul>
            @if(count($viewmodel->unknownAuthorsNames) > 0)
            <div
              id="alert-author-not-found"
              class="alert alert-info"
              data-url="{{ route('addAuthor') }}">
              Le ou les auteurs suivant n'ont pas été trouvés dans la liste :
              <ul id="authors-not-found-list">
                @foreach($viewmodel->unknownAuthorsNames as $name)
                <li>
                    <span class="author-not-found-name">{{ $name }}</span>
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
                @endforeach
              </ul>
              Veuillez les créer ou sélectionner des auteurs existant.
            </div>
            @endif
          </div>

          <!-- Description/Summary -->
          <div class="form-group">
            {!! Form::label('book-description', 'Description') !!}
            {!! Form::textarea('book-description', $viewmodel->description, ['class' => 'form-control', 'id' => 'add-book-form-description']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="panel-footer">
      {!! Form::submit('Ajouter', ['class' => 'pull-right btn btn-success', 'id' => 'add-book-form-submit']) !!}
      <a href="{{route('books')}}" class="btn btn-primary" id="btnGoBack">Annuler</a>
      <a href="{{route('addBookFormStep1')}}" class="btn btn-default"><< Étape 1</a>
      <div class="clearfix"></div>
    </div>
  </div>
  {!! Form::close() !!}

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
@endsection
