<div class="form_wizard wizard_horizontal row">
    <div class="col-xs-6 wizard-step">
        <a href="{{route('addBookFormStep1')}}" class="@if($step==1) selected @endif">
            <span class="step_no">1</span>
            <span class="step_descr">
                Etape 1
                <br>
                <small>Recherche du livre</small>
            </span>
        </a>
    </div>

    <div class="col-xs-6 wizard-step">
        <a href="#" class="@if($step==2) selected @endif">
            <span class="step_no">2</span>
            <span class="step_descr">
                Etape 2
                <br>
                <small>Données du livre</small>
            </span>
        </a>
    </div>
</div>
