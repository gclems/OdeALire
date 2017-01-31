<div
  id="modify-serie-modal"
  class="modal fade"
  tabindex="-1"
  role="dialog">
  <div
    class="modal-dialog"
    role="document">
    {!! Form::open([
        'route' => 'modifySerie',
        'method' => 'post',
        'id' => 'modify-serie-form'
        ]) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-edit"></i>&nbsp;Modifier un éditeur</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              {!! Form::label('modify-serie-name', 'Nom') !!}
              {!! Form::text('modify-serie-name', $serie->Title, ['class' => 'form-control', 'id' => 'modify-serie-form-name']) !!}
              {!! Form::hidden('modify-serie-id', $serie->Id, ['id' => 'modify-serie-id']) !!}
          </div>
        <div id="modify-author-form-error" class="alert alert-danger hide"></div>
        <div class="alert alert-warning hide">
            Un ou plusieurs éditeurs similaires ont été trouvés.
            Voulez-vous tout de même ajouter cet éditeur ?
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-primary"
          data-dismiss="modal">Close</button>
          {!! Form::submit('Modifier', ['class' => 'btn btn-success', 'id' => 'modify-serie-form-submit']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
