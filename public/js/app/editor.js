(function(){
    window.odealire.page = {
      filtersForm: $('#filters-form'),
      editorsListDataSource: $('#filters-form').attr('action'),

      editorsListContainer: $('#editors-list-container'),
      deleteEditorUrl: $('#editors-list-container').data('delete-url'),
      getModifyFormUrl: $('#editors-list-container').data('modify-url'),

      perPageNumberField: $('#filter-per-page-number'),
      orderByColumnField: $('#filter-order-by'),
      orderByDirectionField: $('#filter-order-by-direction'),
      searchEditorField: $('#search-editor-field'),

      resetSearchEditorButton: $('#reset-search-button'),

      currentPageNumber: 1,
      currentPerPageNumber: 1,
      currentOrderByColumn: '',
      currentOrderByDirection: '',
      currentEditorSearchValue: '',

      init : function(){
        window.odealire.page.updateFiltersCurrentValue();

        // Author search field
        window.odealire.page.resetSearchEditorButton.on('click', function(e){
          window.odealire.page.searchEditorField.val('');
        });

        // Filters form
        $('#filters-form').on('submit', function(e){
          e.preventDefault();
          window.odealire.page.updateFiltersCurrentValue();
          window.odealire.page.updateEditorsList(
            window.odealire.page.currentPerPageNumber,
            1,
            window.odealire.page.currentOrderByColumn,
            window.odealire.page.currentOrderByDirection,
            window.odealire.page.currentEditorSearchValue);
        });

        // list pagination
        window.odealire.page.editorsListContainer.on('click', '.pagination button:not(.active)', function(e){
              window.odealire.page.changeListPage($(this).data('page'));
        });

        // Filters
        $('#apply-filter-button').on('click', function(){
           window.odealire.page.updateFiltersCurrentValue();
           window.odealire.page.updateEditorsList(
             window.odealire.page.currentPerPageNumber,
             1,
             window.odealire.page.currentOrderByColumn,
             window.odealire.page.currentOrderByDirection,
             window.odealire.page.currentEditorSearchValue)
        });

        // Add author form
        $('#add-editor-modal').on('shown.bs.modal', function () {
          $('#add-editor-form-name').focus()
        });

        $('#add-editor-form').on('submit', function(e){
            e.preventDefault();
             var $this = $(this);

             var data = $this.serializeArray();
             data.push({name:'maxNumber', value:window.odealire.page.currentPerPageNumber});
             data.push({name:'pageNumber', value:window.odealire.page.currentPageNumber});
             data.push({name:'orderByColumn', value:window.odealire.page.currentOrderByColumn});
             data.push({name:'orderByDirection', value:window.odealire.page.currentOrderByDirection});
             data.push({name:'search', value:window.odealire.page.currentEditorSearchValue});

             $('#add-editor-form :input').attr("disabled", "disabled");
             window.odealire.ajaxPost(
               $this.attr('action'),
               data,
               'Création',
               'L\'éditeur a été ajouté.',
               'Impossible d\'ajouter cet éditeur.',
               function(json){
                     //window.odealire.page.editorsListContainer.html(json.view);
                     $('#add-editor-modal').modal('hide');
                     $this[0].reset();
                     $('#add-editor-form :input').removeAttr("disabled");

                     window.odealire.page.updateEditorsList(
                       window.odealire.page.currentPerPageNumber,
                       window.odealire.page.currentPageNumber,
                       window.odealire.page.currentOrderByColumn,
                       window.odealire.page.currentOrderByDirection,
                       window.odealire.page.currentEditorSearchValue);
                   },
                function(errorMessage){
                  $('#add-editor-form-error').html(errorMessage);
                  $('#add-editor-form-error').removeClass('hide');
                  $('#add-editor-form :input').removeAttr("disabled");
                }
             );
            });

        // modify dialog
        window.odealire.page.editorsListContainer.on('click', '.btn-modify-editor', function(e){
          window.odealire.page.getEditorModifyForm($(this).closest('tr').data('id'))
        });

        // delete button
        window.odealire.page.editorsListContainer.on('click','.btn-delete-editor', function(e){
            window.odealire.page.deleteAuthor($(this).closest('tr'));
        });

        // modify form
        $('body').on('submit','#modify-editor-form', function(e){
            e.preventDefault();
             var $this = $(this);

             var data = $this.serializeArray();
             data.push({name:'maxNumber', value:window.odealire.page.currentPerPageNumber});
             data.push({name:'pageNumber', value:window.odealire.page.currentPageNumber});
             data.push({name:'orderByColumn', value:window.odealire.page.currentOrderByColumn});
             data.push({name:'orderByDirection', value:window.odealire.page.currentOrderByDirection});
             data.push({name:'search', value:window.odealire.page.currentEditorSearchValue});

             $('#modify-editor-form :input').attr("disabled", "disabled");
             window.odealire.ajaxPost(
               $this.attr('action'),
               data,
               'Modification',
               'L\'éditeur a été modifié.',
               'Impossible de modifier cet éditeur.',
               function(json){
                     //window.odealire.page.editorsListContainer.html(json.view);
                     $('#modify-editor-modal').modal('hide');
                     $this[0].reset();
                     $('#modify-editor-form :input').removeAttr("disabled");
                     $('#modify-editor-modal').modal('hide');

                     window.odealire.page.updateEditorsList(
                       window.odealire.page.currentPerPageNumber,
                       window.odealire.page.currentPageNumber,
                       window.odealire.page.currentOrderByColumn,
                       window.odealire.page.currentOrderByDirection,
                       window.odealire.page.currentEditorSearchValue);
                   },
                function(errorMessage){
                  $('#modify-editor-form-error').html(errorMessage);
                  $('#modify-editor-form-error').removeClass('hide');
                  $('#modify-editor-form :input').removeAttr("disabled");

                  $('#modify-editor-modal').modal('hide');
                }
             );
        });
      },

      // Take filters values and store it
      updateFiltersCurrentValue: function(){
        window.odealire.page.currentPerPageNumber = window.odealire.page.perPageNumberField.val();
        window.odealire.page.currentOrderByColumn = window.odealire.page.orderByColumnField.val();
        window.odealire.page.currentOrderByDirection = window.odealire.page.orderByDirectionField.val();
        window.odealire.page.currentEditorSearchValue = window.odealire.page.searchEditorField.val();
      },

      // Change pagination current page and update data
      changeListPage: function(pageNumber){
        window.odealire.page.updateEditorsList(
          window.odealire.page.currentPerPageNumber,
          pageNumber,
          window.odealire.page.currentOrderByColumn,
          window.odealire.page.currentOrderByDirection,
          window.odealire.page.currentEditorSearchValue);
      },

      // Update editors list according to parameters
      updateEditorsList: function(
          maxNumber,
          pageNumber,
          orderByColumn,
          orderByDirection,
          currentSearch){
        window.odealire.ajaxPost(
          window.odealire.page.editorsListDataSource,
          { maxNumber: maxNumber,
            pageNumber: pageNumber,
            orderByColumn: orderByColumn,
            orderByDirection: orderByDirection,
            search: currentSearch },
          null,
          null,
          null,
          function(json){
            window.odealire.page.editorsListContainer.html(json.view);
          });
      },

      getEditorModifyForm: function(editorId){
        // Use ajax to get modify form
        window.odealire.ajaxPost(
          window.odealire.page.getModifyFormUrl,
          { editorId: editorId },
          null,
          null,
          null,
          function(json){
              $('#modify-form-modal-container').html(json.view);
              $('#modify-editor-modal').on('hidden.bs.modal', function (e) {
                $('#modify-form-modal-container').html('');
              });
              $('#modify-editor-modal').on('shown.bs.modal', function () {
                $('#modify-editor-form-name').focus()
              });

              $('#modify-editor-modal').modal('show');
            },
          function(error){

          });
      },

      // Ask confirmation and delete an editor
      deleteAuthor: function(jqRow){
        var id = jqRow.data('id');
        var editorName = jqRow.data('name');

        window.odealire.confirmDialog(
          'Suppression d\'un éditeur',
          'Voulez-vous réellement supprimmer <i>' + editorName + '</i> de la base de données ?',
          {
            icon: 'fa-times',
            confirmCallback: function(){
                window.odealire.ajaxPost(
                  window.odealire.page.deleteEditorUrl,
                  {
                    maxNumber: window.odealire.page.currentPerPageNumber,
                    pageNumber: window.odealire.page.currentPageNumber,
                    orderByColumn: window.odealire.page.currentOrderByColumn,
                    orderByDirection: window.odealire.page.currentOrderByDirection,
                    search: window.odealire.page.currentSearch,
                    editorId: id
                  },
                  'Suppression',
                  editorName + ' a été supprimé.',
                  'Impossible de supprimer ' + editorName,
                  function(json){
                      jqRow.remove();
                  });
            }
        });
    }
}
})();
