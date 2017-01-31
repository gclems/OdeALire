<div
  id="add-author-modal"
  class="modal fade"
  tabindex="-1"
  role="dialog">
  <div
    class="modal-dialog"
    role="document">
    {!! Form::open([
        'route' => 'addAuthor',
        'method' => 'post',
        'id' => 'add-author-form'
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
        <h4 class="modal-title"><i class="fa fa-user-plus"></i>&nbsp;Ajout d'un auteur</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              {!! Form::label('author-name', 'Nom') !!}
              {!! Form::text('author-name', '', ['class' => 'form-control', 'id' => 'add-author-form-name']) !!}
          </div>
        <div id="add-author-form-error" class="alert alert-danger hide"></div>
        <div class="alert alert-warning hide">
            Un ou plusieurs auteurs similaires ont été trouvés.
            Voulez-vous tout de même ajouter cet auteur ?
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-primary"
          data-dismiss="modal">Close</button>
          {!! Form::submit('Ajouter', ['class' => 'btn btn-success', 'id' => 'add-author-form-submit']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
