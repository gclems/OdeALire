(function(){
    window.odealire.page = {
      booksListDataSource: $('#filters-form').attr('action'),

      booksListContainer: $('#books-list-container'),
      deleteBookUrl: $('#books-list-container').data('delete-url'),

      perPageNumberField: $('#filter-per-page-number'),
      orderByColumnField: $('#filter-order-by'),
      orderByDirectionField: $('#filter-order-by-direction'),
      searchBookField: $('#search-book-field'),

      resetSearchBookButton: $('#reset-search-button'),

      currentPageNumber: 1,
      currentPerPageNumber: 1,
      currentOrderByColumn: '',
      currentOrderByDirection: '',
      currentAuthorSearchValue: '',

      init : function(){
        window.odealire.page.updateFiltersCurrentValue();

        // Search field
        window.odealire.page.resetSearchBookButton.on('click', function(e){
          window.odealire.page.searchBookField.val('');
        });

        // Filters form
        $('#filters-form').on('submit', function(e){
          e.preventDefault();
          window.odealire.page.updateFiltersCurrentValue();
          window.odealire.page.updateBooksList(
            window.odealire.page.currentPerPageNumber,
            1,
            window.odealire.page.currentOrderByColumn,
            window.odealire.page.currentOrderByDirection,
            window.odealire.page.currentBookSearchValue);
        });

        // list pagination
        window.odealire.page.booksListContainer.on('click', '.pagination button:not(.active)', function(e){
              window.odealire.page.changeListPage($(this).data('page'));
        });

        // more info button
        $('body').on('click', '.btn-book-info', function(e){
          window.odealire.dialog('En développement', 'Patience et boule de gomme.');
        });

        // delete button
        window.odealire.page.booksListContainer.on('click','.btn-delete-book', function(e){
          window.odealire.page.deleteBook($(this).closest('tr'));
        });
      },

      // Take filters values and store it
      updateFiltersCurrentValue: function(){
        window.odealire.page.currentPerPageNumber = window.odealire.page.perPageNumberField.val();
        window.odealire.page.currentOrderByColumn = window.odealire.page.orderByColumnField.val();
        window.odealire.page.currentOrderByDirection = window.odealire.page.orderByDirectionField.val();
        window.odealire.page.currentBookSearchValue = window.odealire.page.searchBookField.val();
      },

      // Change pagination current page and update data
      changeListPage: function(pageNumber){
        window.odealire.page.updateBooksList(
          window.odealire.page.currentPerPageNumber,
          pageNumber,
          window.odealire.page.currentOrderByColumn,
          window.odealire.page.currentOrderByDirection,
          window.odealire.page.currentBookSearchValue);
      },

      // Update books list according to parameters
      updateBooksList: function(
        maxNumber,
        pageNumber,
        orderByColumn,
        orderByDirection,
        currentSearch){
        window.odealire.ajaxPost(
          window.odealire.page.booksListDataSource,
          { maxNumber: maxNumber,
            pageNumber: pageNumber,
            orderByColumn: orderByColumn,
            orderByDirection: orderByDirection,
            search: currentSearch },
          null,
          null,
          null,
          function(json){
            window.odealire.page.booksListContainer.html(json.view);
          });
      },

      // Ask confirmation and delete a book
      deleteBook: function(jqRow){
        var bookId = jqRow.data('id');
        var bookTitle = jqRow.data('title');

        window.odealire.confirmDialog(
          'Suppression d\'un auteur',
          'Voulez-vous réellement supprimmer <i>' + bookTitle + '</i> de la base de données ?',
          {
            icon: 'fa-times',
            confirmCallback: function(){
                window.odealire.ajaxPost(
                  window.odealire.page.deleteBookUrl,
                  {
                    bookId: bookId
                  },
                  'Suppression',
                  bookTitle + ' a été supprimé.',
                  'Impossible de supprimer ' + bookTitle,
                  function(json){
                    jqRow.remove();
                  });
            }
          });
        }
    }
})();
