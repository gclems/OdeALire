<div class="book-search-result">
  <div>
    <div class="pull-right">Source : {{$viewmodel->source}}</div>
    <div class="title m-b-1">{{$viewmodel->title}}</div>
  </div>
  <div class="content row">
      <div class="col-lg-4">
        <div>
          <strong>ISBN 13 :</strong>
          {{$viewmodel->isbn}}
        </div>
        <div>
          <strong>Éditeur :</strong>
          {{$viewmodel->editorName}}
        </div>
        <div>
          <strong>@if(isset($viewmodel->authorsNames) && count($viewmodel->authorsNames) > 1) Auteurs @else Auteur @endif :</strong>
          <ul>
            @if(isset($viewmodel->authorsNames))
              @foreach($viewmodel->authorsNames as $author)
                <li>{{$author}}</li>
              @endforeach
            @endif
          </ul>
        </div>
      </div>
      <div class="col-lg-8">
        <div>
          <strong>Description :</strong>
        </div>
        <div>
          {{ $viewmodel->description }}
        </div>
      </div>
  </div>
</div>
