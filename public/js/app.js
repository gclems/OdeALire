(function(){
    window.odealire = {
        // The overlay DOM element
        overlay: $('#overlay'),

        // Number of overlay display calls
        overlayNumber: 0,

        // Initializes the object.
        init: function(){
            // automatically add token in ajax requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Init PNotify for Bootstrap3 & fontawesome
            PNotify.prototype.options.styling = "fontawesome";

            $('.side-menu').on('click', '> li.nav-container:not(.active) > a', function(){
              $('.side-menu > li.nav-container.active > ul.child_menu').slideUp();
              $('.side-menu > li.active').removeClass('active');

              $(this).siblings('ul.child_menu').slideDown();
              $(this).closest('li').addClass('active');
            });

            $('#btn-toggle-navbar, #left-panel-overlay').on('click', function(){
                /*var val = $('#left-panel-overlay').hasClass('active') ? '-230px' : '0';
                $('#left-panel').animate({left: val}, 200);*/
                $('#left-panel').toggleClass('active');
                $('#left-panel-overlay').toggleClass('active');
            });

            $('body').on('click','.closePanelButton', function(e){
              var button = $(this);
              var panel = button.parents('.panel');
              panel.toggleClass('closed');
              panel.children('.panel-body').toggle();

              var icon = $(this).children('i.fa');
              if(icon.hasClass('fa-angle-up')){
                icon.removeClass('fa-angle-up');
                icon.addClass('fa-angle-down');
              }
              else{
                icon.removeClass('fa-angle-down');
                icon.addClass('fa-angle-up');
              }
            });

            window.odealire.initNumbersInput();

            window.odealire.page.init();
        },

        // Represents the current page object.
        page: {
            init: function(){}
        },

        // Displays the overlay, with a delay (in ms)
        showOverlay: function(delay){
          window.odealire.overlayNumber++;

          if(window.odealire.overlayNumber === 1){
            if(delay === null || delay === 0){
              window.odealire.overlay.removeClass('hide');
            }
            else{
              setTimeout(function(){
                if(window.odealire.overlayNumber > 0){
                  window.odealire.overlay.removeClass('hide');
                }
               }, delay);
            }
          }
        },

        // Hides the overlay
        hideOverlay: function(){
          if(window.odealire.overlayNumber > 0){
            window.odealire.overlayNumber--;
          }

          if(window.odealire.overlayNumber == 0){
            window.odealire.overlay.addClass('hide');
          }
        },

        // Ajax post
        ajaxPost: function(url, data, successTitle, successMessage, errorMessage, successCallback, errorCallback, showOverlay){
          window.odealire.ajax(url, 'post', data, successTitle, successMessage, errorMessage, successCallback, errorCallback, showOverlay);
        },

        // Ajax get
        ajaxGet: function(url, successTitle, successMessage, errorMessage, successCallback, errorCallback, showOverlay){
          window.odealire.ajax(url, 'get', null, successTitle,  successMessage, errorMessage, successCallback, errorCallback, showOverlay);
        },

        // Ajax call
        ajax: function(url, type, data, successTitle, successMessage, errorMessage, successCallback, errorCallback, showOverlay){
          if(showOverlay == null || showOverlay === true){
              window.odealire.showOverlay(500);
          }
          $.ajax({
            url: url,
            type: type,
            data: data,
            success: function(data, textStatus, jqXHR){
              var json = $.parseJSON(jqXHR.responseText);
              if(json.success){
                  if(successMessage != undefined && successMessage != null && successMessage != ""){
                    new PNotify({
                      title: successTitle,
                      text: successMessage,
                      type: 'success'
                    });
                  }
                  if(typeof(successCallback) === 'function'){
                    successCallback(json);
                  }
              }
              else{
                  if(typeof(errorCallback) === 'function'){
                   errorCallback(json.error);
                  }

                 if(errorMessage == null){
                   errorMessage = 'Une erreur est survenue.'
                 }

                 new PNotify({
                   title: errorMessage,
                   text: json.error,
                   type: 'error'
                 });
              }
            },
            error: function(jqXHR, textStatus, errorThrown){
                if(typeof(errorCallback) === 'function'){
                 errorCallback(errorThrown);
                }

                if(errorMessage == null){
                  errorMessage = 'Une erreur est survenue.'
                }

                new PNotify({
                  title: errorMessage,
                  text: errorThrown,
                  type: 'error'
                });
            },
            complete: function(){
                if(showOverlay == null || showOverlay === true){
                    window.odealire.hideOverlay();
                }
            }
          });
        },

        // Shows a confirm dialog
        confirmDialog: function(title, content, options){
          var opts = {
            icon: '',
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'annuler',
            confirmCallback: function(){},
            cancelCallback: function(){}
          };

          $.extend(opts, options || {});

          var $modal = $('<div class="modal fade" tabindex="-1" role="dialog"></div>');
          var $modalDialog = $('<div class="modal-dialog"></div>');
          var $modalContent = $('<div class="modal-content"></div>');
          var $modalFooter = $('<div class="modal-footer"></div>')
          var $confirmButton = $('<button type="button" class="btn btn-success">' + opts.confirmButtonText + '</button>');
          var $cancelButton = $('<button type="button" class="btn btn-primary">' + opts.cancelButtonText + '</button>');

          $confirmButton.click(function(){
            if(typeof (opts.confirmCallback) === "function"){
              opts.confirmCallback();
            }

            $modal.modal('hide');
          });

          $cancelButton.click(function(){
            if(typeof (opts.cancelCallback) === "function"){
              opts.cancelCallback();
            }

            $modal.modal('hide');
          });

          $modalFooter.append($cancelButton);
          $modalFooter.append($confirmButton);

          $modalContent.append('<div class="modal-header">'
                       + '<h4 class="modal-title"><i class="fa ' + opts.icon + '"></i>&nbsp;' + title +'</h4></div>'
                       + '<div class="modal-body">' + content + '</div>');
         $modalContent.append($modalFooter);

          $modalDialog.append($modalContent);
          $modal.append($modalDialog);
          $modal.modal('show');
        },

        // Show a simple dialog
        dialog: function(title, content, icon, buttonText, callback){

            if(icon == null){
                icon = 'fa fa-warning';
            }

            if(buttonText == null || buttonText == ''){
                buttonText = 'Fermer';
            }

            var $modal = $('<div class="modal fade" tabindex="-1" role="dialog"></div>');
            var $modalDialog = $('<div class="modal-dialog"></div>');
            var $modalContent = $('<div class="modal-content"></div>');
            var $modalFooter = $('<div class="modal-footer"></div>')
            var $cancelButton = $('<button type="button" class="btn btn-primary">' + buttonText + '</button>');

            $cancelButton.click(function(){
              if(typeof (callback) === "function"){
                callback();
              }

              $modal.modal('hide');
            });

            $modalFooter.append($cancelButton);

            $modalContent.append('<div class="modal-header">'
                         + '<h4 class="modal-title"><i class="fa ' + icon + '"></i>&nbsp;' + title +'</h4></div>'
                         + '<div class="modal-body">' + content + '</div>');
            $modalContent.append($modalFooter);

            $modalDialog.append($modalContent);
            $modal.append($modalDialog);
            $modal.modal('show');
        },

        initNumbersInput: function(){
          $.each($('.numberInputContainer'), function(index, item){
            var container = $(item);
            var input = container.find('.numberInput');
            var substractButton = container.find('.numberInputSubstract');
            var addButton = container.find('.numberInputAdd');

            if(substractButton == null || addButton == null){
              alert('Number input error : one of the necessary button is missing.');
              return;
            }

            var minValue = substractButton.data('min');
            var maxValue = addButton.data('max');

            if(minValue != null && !Number.isInteger(minValue)){
                minValue = null;
            }

            if(maxValue != null && !Number.isInteger(maxValue)){
                maxValue = null;
            }

            if(input.val() == null || input.val() == ''){
              var finalValue = 0;
              if(minValue != null){
                finalValue = Math.max(finalValue, minValue);
              }

              if(maxValue != null){
                finalValue = Math.min(finalValue, maxValue);
              }

              input.val(finalValue);
            }
            else if(minValue != null && parseInt(input.val()) < minValue){
              input.val(minValue);
            }
            else if(maxValue != null && parseInt(input.val()) > maxValue){
              input.val(maxValue);
            }

            substractButton.click(function(e){
              var finalValue = parseInt(input.val()) -1;
              if(minValue != null){
                finalValue = Math.max(finalValue, minValue);
              }

              input.val(finalValue);
            });

            addButton.click(function(e){
              var finalValue = parseInt(input.val()) +1 ;
              if(maxValue != null){
                finalValue = Math.min(finalValue, maxValue);
              }

              input.val(finalValue);
            });
          });
        },
    }

    $(document).ready(window.odealire.init);
})();
