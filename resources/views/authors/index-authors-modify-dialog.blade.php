<div
  id="modify-author-modal"
  class="modal fade"
  tabindex="-1"
  role="dialog">
  <div
    class="modal-dialog"
    role="document">
    {!! Form::open([
        'route' => 'modifyAuthor',
        'method' => 'post',
        'id' => 'modify-author-form'
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
        <h4 class="modal-title"><i class="fa fa-edit"></i>&nbsp;Modifier un auteur</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              {!! Form::label('modify-author-name', 'Nom') !!}
              {!! Form::text('modify-author-name', $author->Name, ['class' => 'form-control', 'id' => 'modify-author-form-name']) !!}
              {!! Form::hidden('modify-author-id',$author->Id, ['id' => 'modify-author-id']) !!}
          </div>
        <div id="modify-author-form-error" class="alert alert-danger hide"></div>
        <div class="alert alert-warning hide">
            Un ou plusieurs auteurs similaires ont été trouvés.
            Voulez-vous tout de même ajouter cet auteur ?
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-success"
          data-dismiss="modal">Close</button>
          {!! Form::submit('Modifier', ['class' => 'btn btn-primary', 'id' => 'modify-author-form-submit']) !!}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
