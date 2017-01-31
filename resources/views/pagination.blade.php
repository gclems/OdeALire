<nav aria-label="Page navigation">
  <div class="btn-group pagination">
    <button
      class="btn btn-default" @if($viewmodel->currentPageNumber <= 1) disabled="disabled" @endif
      data-page="1"><span aria-hidden="true">&laquo;</span></button>

    <button
      class="btn @if($viewmodel->currentPageNumber <= 1) btn-success active @else btn-default @endif"
      data-page='1'>1</button>
    @for($i = 2; $i <= $viewmodel->getNumberOfPages(); $i++)
    <button class="btn @if($viewmodel->currentPageNumber == $i) btn-success active @else btn-default @endif" data-page='{{$i}}'>{{$i}}</button>
    @endfor
    <button
      class="btn btn-default" @if($viewmodel->currentPageNumber >= $viewmodel->getNumberOfPages()) disabled="disabled" @endif
      data-page="{{$viewmodel->getNumberOfPages()}}"><span aria-hidden="true">&raquo;</span></button>
  </div>
</nav>
