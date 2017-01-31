<div
  id="add-serie-modal"
  class="modal fade"
  tabindex="-1"
  role="dialog">
  <div
    class="modal-dialog"
    role="document">
    {!! Form::open([
        'route' => 'addSerie',
        'method' => 'post',
        'id' => 'add-serie-form'
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
        <h4 class="modal-title"><i class="fa fa-building"></i>&nbsp;Ajout d'une série</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              {!! Form::label('serie-name', 'Titre') !!}
              {!! Form::text('serie-name', '', ['class' => 'form-control', 'id' => 'add-serie-form-name']) !!}
          </div>
        <div id="add-serie-form-error" class="alert alert-danger hide"></div>
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
          {!! Form::submit('Ajouter', ['class' => 'btn btn-success', 'id' => 'add-serie-form-submit']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
