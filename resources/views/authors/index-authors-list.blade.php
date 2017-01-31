<div class="row table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr><th>Nom</th><th>Livres</th><th>Ajouté le</th><th>Par</th><th></th></tr>
    </thead>
    <tbody>
      @foreach($viewmodel->authors as $author)
      <tr data-id='{{ $author->Id }}' data-name='{{ $author->Name }}'>
          <td>{{ $author->Name }}</td>
          <td>{{ $author->books_count }}</td>
          <td>{{ $author->CreatedAt->format(Config::get('constants.dateTimeFormat')) }}</td>
          <td>{{ $author->creator->Name }}</td>
          <td>
              <button
                type="button"
                class="btn btn-warning btn-xs btn-modify-author"
                title="Modifier"><i class="fa fa-edit"></i></button>
            @if($author->books_count == 0)
              <button
                type="button"
                class="btn btn-danger btn-xs btn-delete-author"
                title="Supprimer"><i class="fa fa-times"></i></button>
            @endif
          </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div
  id="authors-list-pagination-container">
  @include('pagination', ['viewmodel' => $viewmodel->paginationViewmodel])
</div>
