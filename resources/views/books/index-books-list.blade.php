<div class="row table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Isbn</th>
        <th>Titre</th>
        <th>Auteur(s)</th>
        <th>Série</th>
        <th>Éditeur</th>
        <th>Date ajout</th>
      </tr>
    </thead>
    <tbody>
      @foreach($viewmodel->books as $book)
      <tr
        data-id='{{ $book->Id }}'
        data-title='{{ $book->Title }}'>
          <td>{{ $book->Isbn }}</td>
          <td><strong>{{ $book->Title }}</strong></td>
          <td>
            @if($book->authors->count() == 1)
              {{ $book->authors[0]->Name }}
            @else
            <ul class="list-unstyled m-b-0">
              @foreach($book->authors as $author)
              <li>{{ $author->Name }}</li>
              @endforeach
            </ul>
            @endif
          </td>
          <td>@if(isset($book->serie)){{ $book->serie->Title }}@endif</td>
          <td>@if(isset($book->editor)){{ $book->editor->Name }}@endif</td>
          <td>{{ $book->CreatedAt->format(Config::get('constants.dateTimeFormat')) }}</td>
          <td>
            @if($book->loans_count == 0)
              <a
                href=""
                class="btn btn-success btn-xs"
                title="Prêter">
                <i class="fa fa-exchange"></i>
              </a>
            @endif
              <a
                href="{{ route('editBookForm', $book->Id) }}"
                class="btn btn-warning btn-xs btn-modify-book"
                title="Modifier"><i class="fa fa-edit"></i>
              </a>
              <button
                type="button"
                class="btn btn-danger btn-xs btn-delete-book"
                title="Supprimer"><i class="fa fa-times"></i>
              </button>
              <button
                type="button"
                class="btn btn-info btn-xs m-l-1 btn-book-info"
                title="Informations complémentaires">
                <i class="fa fa-info"></i>
              </button>
          </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div
  id="books-list-pagination-container">
  @include('pagination', ['viewmodel' => $viewmodel->paginationViewmodel])
</div>
