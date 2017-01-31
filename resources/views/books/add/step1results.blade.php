@if(!$viewmodel->googleSearchState)
  <div class="alert alert-danger">
    Une erreur est survenue lors de la recherche dans la base de données Google.
    @if(isset($viewmodel->googleError))
    <br />{{ $viewmodel->googleError }}
    @endif
  </div>
@endif
@if(!$viewmodel->isbnDBSearchState)
  <div class="alert alert-danger">
    Une erreur est survenue lors de la recherche dans la base de données IsbnDB.
    @if(isset($viewmodel->isbnDBError))
    <br />{{ $viewmodel->isbnDBError }}
    @endif
  </div>
@endif
@if(!$viewmodel->worldCatSearchState)
  <div class="alert alert-danger">
    Une erreur est survenue lors de la recherche dans la base de données WorldCat.
  </div>
@endif

  @if(isset($viewmodel->searchResults))
    @foreach($viewmodel->searchResults as $result)
      <div
        class="well book-search-result-container"
        data-isbn='{{$result->isbn}}'
        data-title='{{$result->title}}'
        data-publisher='{{$result->editorName}}'
        data-authors='@foreach($result->authorsNames as $author) {{$author}}; @endforeach'
        data-description='{{$result->description}}'>
          @include('books.add.step1result', ['viewmodel' => $result])
      </div>
    @endforeach
  @endif
