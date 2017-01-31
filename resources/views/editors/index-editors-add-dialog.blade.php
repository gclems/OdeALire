<div
  id="add-editor-modal"
  class="modal fade"
  tabindex="-1"
  role="dialog">
  <div
    class="modal-dialog"
    role="document">
    {!! Form::open([
        'route' => 'addEditor',
        'method' => 'post',
        'id' => 'add-editor-form'
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
        <h4 class="modal-title"><i class="fa fa-building"></i>&nbsp;Ajout d'un éditeur</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              {!! Form::label('editor-name', 'Nom') !!}
              {!! Form::text('editor-name', '', ['class' => 'form-control', 'id' => 'add-editor-form-name']) !!}
          </div>
        <div id="add-editor-form-error" class="alert alert-danger hide"></div>
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
          {!! Form::submit('Ajouter', ['class' => 'btn btn-success', 'id' => 'add-editor-form-submit']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
