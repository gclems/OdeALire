<div class="row table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr><th>Nom</th><th>Livres</th><th>Ajouté le</th><th>Par</th><th></th></tr>
    </thead>
    <tbody>
      @foreach($viewmodel->editors as $editor)
      <tr data-id="{{ $editor->Id }}" data-name="{{ $editor->Name }}">
          <td>{{ $editor->Name }}</td>
          <td>{{ $editor->books_count }}</td>
          <td>{{ $editor->CreatedAt->format(Config::get('constants.dateTimeFormat')) }}</td>
          <td>{{ $editor->creator->Name }}</td>
          <td>
              <button
                type="button"
                class="btn btn-warning btn-xs btn-modify-editor"
                title="Modifier"><i class="fa fa-edit"></i></button>
            @if($editor->books_count == 0)
              <button
                type="button"
                class="btn btn-danger btn-xs btn-delete-editor"
                title="Supprimer"><i class="fa fa-times"></i></button>
            @endif
          </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div
  id="editors-list-pagination-container">
  @include('pagination', ['viewmodel' => $viewmodel->paginationViewmodel])
</div>
