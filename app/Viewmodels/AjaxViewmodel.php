<?php
namespace App\Viewmodels;

class AjaxViewmodel{
    public $success = true;
    public $error = null;
    public $view = null;

    function __construct($isSuccessful, $errorMessage, $renderedView){
        $this->success = $isSuccessful;
        $this->error = $errorMessage;
        $this->view = $renderedView;
    }
}
