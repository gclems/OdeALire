(function(){
    window.odealire.page = {
        filtersForm: $('#filters-form'),
        authorsListDataSource: $('#filters-form').attr('action'),

        authorsListContainer: $('#authors-list-container'),
        deleteAuthorUrl: $('#authors-list-container').data('delete-url'),
        getModifyFormUrl: $('#authors-list-container').data('modify-url'),

        perPageNumberField: $('#filter-per-page-number'),
        orderByColumnField: $('#filter-order-by'),
        orderByDirectionField: $('#filter-order-by-direction'),
        searchAuthorField: $('#search-author-field'),

        resetSearchAuthorButton: $('#reset-search-button'),

        currentPageNumber: 1,
        currentPerPageNumber: 1,
        currentOrderByColumn: '',
        currentOrderByDirection: '',
        currentAuthorSearchValue: '',

        init : function(){
          window.odealire.page.updateFiltersCurrentValue();

          // Author search field
          window.odealire.page.resetSearchAuthorButton.on('click', function(e){
            window.odealire.page.searchAuthorField.val('');
          });

          // Filters form
          $('#filters-form').on('submit', function(e){
            e.preventDefault();
            window.odealire.page.updateFiltersCurrentValue();
            window.odealire.page.updateAuthorsList(
              window.odealire.page.currentPerPageNumber,
              1,
              window.odealire.page.currentOrderByColumn,
              window.odealire.page.currentOrderByDirection,
              window.odealire.page.currentAuthorSearchValue);
          });

          // Add author form
          $('#add-author-modal').on('shown.bs.modal', function () {
            $('#add-author-form-name').focus()
          });

          $('#add-author-form').on('submit', function(e){
              e.preventDefault();
               var $this = $(this);

               var data = $this.serializeArray();
               data.push({name:'maxNumber', value:window.odealire.page.currentPerPageNumber});
               data.push({name:'pageNumber', value:window.odealire.page.currentPageNumber});
               data.push({name:'orderByColumn', value:window.odealire.page.currentOrderByColumn});
               data.push({name:'orderByDirection', value:window.odealire.page.currentOrderByDirection});
               data.push({name:'search', value:window.odealire.page.currentAuthorSearchValue});

               $('#add-author-form :input').attr("disabled", "disabled");
               window.odealire.ajaxPost(
                 $this.attr('action'),
                 data,
                 'Création',
                 'L\'auteur a été ajouté.',
                 'Impossible d\'ajouter cet auteur.',
                 function(json){
                       $('#add-author-modal').modal('hide');
                       $this[0].reset();
                       $('#add-author-form :input').removeAttr("disabled");

                       window.odealire.page.updateAuthorsList(
                         window.odealire.page.currentPerPageNumber,
                         window.odealire.page.currentPageNumber,
                         window.odealire.page.currentOrderByColumn,
                         window.odealire.page.currentOrderByDirection,
                         window.odealire.page.currentAuthorSearchValue);
                     },
                  function(errorMessage){
                    $('#add-author-form-error').html(errorMessage);
                    $('#add-author-form-error').removeClass('hide');
                    $('#add-author-form :input').removeAttr("disabled");
                  }
               );
              });

          // list pagination
          window.odealire.page.authorsListContainer.on('click', '.pagination button:not(.active)', function(e){
                window.odealire.page.changeListPage($(this).data('page'));
          });

          // delete button
          window.odealire.page.authorsListContainer.on('click','.btn-delete-author', function(e){
            window.odealire.page.deleteAuthor($(this).closest('tr'));
          });

          // modify dialog
          window.odealire.page.authorsListContainer.on('click', '.btn-modify-author', function(e){
            window.odealire.page.getAuthorModifyForm( $(this).closest('tr').data('id'));
          });

          // modify form
          $('body').on('submit','#modify-author-form', function(e){
              e.preventDefault();
               var $this = $(this);

               var data = $this.serializeArray();
               data.push({name:'maxNumber', value:window.odealire.page.currentPerPageNumber});
               data.push({name:'pageNumber', value:window.odealire.page.currentPageNumber});
               data.push({name:'orderByColumn', value:window.odealire.page.currentOrderByColumn});
               data.push({name:'orderByDirection', value:window.odealire.page.currentOrderByDirection});
               data.push({name:'search', value:window.odealire.page.currentAuthorSearchValue});

               $('#modify-author-form :input').attr("disabled", "disabled");
               window.odealire.ajaxPost(
                 $this.attr('action'),
                 data,
                 'Modification',
                 'L\'auteur a été modifié.',
                 'Impossible de modifier cet auteur.',
                 function(json){
                       $('#modify-author-modal').modal('hide');
                       $this[0].reset();
                       $('#modify-author-form :input').removeAttr("disabled");
                       $('#modify-author-modal').modal('hide');

                       window.odealire.page.updateAuthorsList(
                         window.odealire.page.currentPerPageNumber,
                         window.odealire.page.currentPageNumber,
                         window.odealire.page.currentOrderByColumn,
                         window.odealire.page.currentOrderByDirection,
                         window.odealire.page.currentAuthorSearchValue);
                     },
                  function(errorMessage){
                    $('#modify-author-form-error').html(errorMessage);
                    $('#modify-author-form-error').removeClass('hide');
                    $('#modify-author-form :input').removeAttr("disabled");

                    $('#modify-author-modal').modal('hide');
                  }
               );
              });
        },

        // Take filters values and store it
        updateFiltersCurrentValue: function(){
          window.odealire.page.currentPerPageNumber = window.odealire.page.perPageNumberField.val();
          window.odealire.page.currentOrderByColumn = window.odealire.page.orderByColumnField.val();
          window.odealire.page.currentOrderByDirection = window.odealire.page.orderByDirectionField.val();
          window.odealire.page.currentAuthorSearchValue = window.odealire.page.searchAuthorField.val();
        },

        // Change pagination current page and update data
        changeListPage: function(pageNumber){
          window.odealire.page.updateAuthorsList(
            window.odealire.page.currentPerPageNumber,
            pageNumber,
            window.odealire.page.currentOrderByColumn,
            window.odealire.page.currentOrderByDirection,
            window.odealire.page.currentAuthorSearchValue);
        },

        // Update authors list according to parameters
        updateAuthorsList: function(
          maxNumber,
          pageNumber,
          orderByColumn,
          orderByDirection,
          currentSearch){
          window.odealire.ajaxPost(
            window.odealire.page.authorsListDataSource,
            { maxNumber: maxNumber,
              pageNumber: pageNumber,
              orderByColumn: orderByColumn,
              orderByDirection: orderByDirection,
              search: currentSearch },
            null,
            null,
            null,
            function(json){
              window.odealire.page.authorsListContainer.html(json.view);
            });
        },

        getAuthorModifyForm: function(authorId){
          // Use ajax to get modify form
          window.odealire.ajaxPost(
            window.odealire.page.getModifyFormUrl,
            { authorId: authorId },
            null,
            null,
            null,
            function(json){
                $('#modify-form-modal-container').html(json.view);
                $('#modify-author-modal').on('hidden.bs.modal', function (e) {
                  $('#modify-form-modal-container').html('');
                });
                $('#modify-author-modal').on('shown.bs.modal', function () {
                  $('#modify-author-form-name').focus()
                });

                $('#modify-author-modal').modal('show');
              },
            function(error){

            });
        },

        // Ask confirmation and delete an author
        deleteAuthor: function(jqRow){
          var authorId = jqRow.data('id');
          var authorName = jqRow.data('name');

          window.odealire.confirmDialog(
            'Suppression d\'un auteur',
            'Voulez-vous réellement supprimmer <i>' + authorName + '</i> de la base de données ?',
            {
              icon: 'fa-times',
              confirmCallback: function(){
                  window.odealire.ajaxPost(
                    window.odealire.page.deleteAuthorUrl,
                    {
                      authorId: authorId
                    },
                    'Suppression',
                    authorName + ' a été supprimé.',
                    'Impossible de supprimer ' + authorName,
                    function(json){
                      jqRow.remove();
                    });
              }
            });
        }
    };
})();
