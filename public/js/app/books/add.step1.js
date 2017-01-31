(function(){
    window.odealire.page = {
      searchBookForm: $('#search-book-form'),
      searchResultsContainer: $('#search-results-panel'),
      searchResultsDisplay: $('#search-results-body'),

      searchResultsButtonsSelector: '.book-search-result-container',

      nextStepButton: $('#btn-go-to-step-2'),
      nextStepForm: $('#select-book-form'),
      nextStepFormIsbn: $('#select-book-isbn'),
      nextStepFormTitle: $('#select-book-title'),
      nextStepFormPublisher: $('#select-book-publisher'),
      nextStepFormAuthors: $('#select-book-authors'),
      nextStepFormDescription: $('#select-book-description'),

      init: function(){
        window.odealire.page.initSearchForm();
        window.odealire.page.initGoToStep2();
      },

      initGoToStep2: function(){
        window.odealire.page.nextStepButton.on('click', function(e){
          window.odealire.page.goToStep2();
        });

        $('body').on('click', window.odealire.page.searchResultsButtonsSelector, function(){
            window.odealire.page.goToStep2(
              $(this).attr('data-isbn'),
              $(this).attr('data-title'),
              $(this).attr('data-publisher'),
              $(this).attr('data-authors'),
              $(this).attr('data-description'));
        });
      },

      goToStep2: function(isbn, title, publisher, authors, description){
        window.odealire.page.nextStepFormIsbn.val(isbn);
        window.odealire.page.nextStepFormTitle.val(title);
        window.odealire.page.nextStepFormPublisher.val(publisher);
        window.odealire.page.nextStepFormAuthors.val(authors);
        window.odealire.page.nextStepFormDescription.val(description);

        window.odealire.page.nextStepForm.submit();
      },

      initSearchForm: function(){
        window.odealire.page.searchBookForm.on('submit', function(e){
         e.preventDefault();
         var $this = $(this);
         var data = $this.serializeArray();

         window.odealire.ajaxPost(
           $this.attr('action'),
           data,
           null,
           null,
           null,
           function(json){
             window.odealire.page.searchResultsContainer.removeClass('hide');
             window.odealire.page.searchResultsDisplay.html(json.view);
          },
          function(errorMessage){

          }
         );
       });
      }
    }
})();
