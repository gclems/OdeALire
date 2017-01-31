@extends('layouts.app')
@section('title', 'Gestion des livres')
@section('h1','<i class="fa fa-book"></i>&nbsp;Modifier un livre')
@section('breadcrumbs')
  <li><a href="{{route('books')}}">Livres</a></li>
  <li>Modification</li>
@endsection
@section('scripts')
<script src="{{ URL::asset('js/app/book-edit.js') }}"></script>
<script id="book-authors-source" type="application/json">
{!! $viewmodel->book->authors->transform(function($item, $key){
  return ["Id" => $item->Id, "Name" => $item->Name];
})->toJson() !!}
</script>
@endsection
@section('content')
<div class="row">
  {!! Form::open([
      'route' => 'editBook',
      'method' => 'post',
      'id' => 'book-form',
      ]) !!}
    <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-pencil"></i>&nbsp;Informations
        </div>
        <div class="panel-body">
            {!! Form::hidden('book-id', $viewmodel->book->Id, ['id' => 'edit-book-id']) !!}
          <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('book-title', 'Titre') !!}
                  {!! Form::text('book-title', $viewmodel->book->Title, ['class' => 'form-control', 'id' => 'edit-book-form-title']) !!}
                </div>
                <div class="form-group">
                  {!! Form::label('book-isbn', 'Isbn') !!}
                  {!! Form::text('book-isbn', $viewmodel->book->Isbn, ['class' => 'form-control', 'id' => 'edit-book-form-isbn']) !!}
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
                    isset($viewmodel->book->editor) ? $viewmodel->book->editor->Id : '',
                    ['class' => 'form-control',
                     'id'=>'edit-book-form-editorId',
                     'placeholder' => '']) !!}
                  <div class="alert alert-info hide" id="alert-editor-not-found">
                    L'éditeur suivant n'a pas été trouvé dans la liste. Veuillez le créer ou sélectionner un éditeur existant.
                    <br />
                    <i><span id="editor-not-found-name"></span></i>
                    <button
                      id="btn-edit-new-editor"
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
                        isset($viewmodel->book->serie) ? $viewmodel->book->serie->Id : '',
                        ['class' => 'form-control',
                         'id'=>'edit-book-form-serieId',
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
                        {!! Form::text('book-serie-number', (isset($viewmodel->book->SerieNumber) ? $viewmodel->book->SerieNumber : ''), ['class' => 'form-control numberInput', 'id' => 'edit-book-form-serieNumber', 'disabled' => 'disabled']) !!}
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
              </div>
              <div class="form-group">
                {!! Form::label('book-description', 'Description') !!}
                {!! Form::textarea('book-description', $viewmodel->book->Description, ['class' => 'form-control', 'id' => 'edit-book-form-description']) !!}
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <div class="pull-right">
          <a href="{{route('books')}}" class="btn btn-primary" id="btnGoBack">Annuler</a>
          {!! Form::submit('Enregistrer', ['class' => 'btn btn-success', 'id' => 'edit-book-form-submit']) !!}
        </div>
          <div class="clearfix"></div>
        </div>
    </div>
    {!! Form::close() !!}
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
@endsection
