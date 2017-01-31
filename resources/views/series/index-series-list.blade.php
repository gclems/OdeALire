<div class="row table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr><th>Titre</th><th>Volumes</th><th>Ajoutée le</th><th>Par</th><th></th></tr>
    </thead>
    <tbody>
      @foreach($viewmodel->series as $serie)
      <tr data-id="{{ $serie->Id }}" data-title="{{ $serie->Title }}">
          <td>{{ $serie->Title }}</td>
          <td>{{ $serie->books_count }}</td>
          <td>{{ $serie->CreatedAt->format(Config::get('constants.dateTimeFormat')) }}</td>
          <td>{{ $serie->creator->Name }}</td>
          <td>
              <button
                type="button"
                class="btn btn-warning btn-xs btn-modify-serie"
                title="Modifier"
                data-serie-id="{{$serie->Id}}"
                data-serie-name="{{$serie->Name}}"><i class="fa fa-edit"></i></button>
            @if($serie->books_count == 0)
              <button
                type="button"
                class="btn btn-danger btn-xs btn-delete-serie"
                title="Supprimer"
                data-serie-id="{{$serie->Id}}"
                data-serie-name="{{$serie->Name}}"><i class="fa fa-times"></i></button>
            @endif
          </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div
  id="series-list-pagination-container">
  @include('pagination', ['viewmodel' => $viewmodel->paginationViewmodel])
</div>
