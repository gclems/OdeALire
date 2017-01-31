(function(){
    window.odealire.page = {
      filtersForm: $('#filters-form'),
      seriesListDataSource: $('#filters-form').attr('action'),

      seriesListContainer: $('#series-list-container'),
      deleteSerieUrl: $('#series-list-container').data('delete-url'),
      getModifyFormUrl: $('#series-list-container').data('modify-url'),

      perPageNumberField: $('#filter-per-page-number'),
      orderByColumnField: $('#filter-order-by'),
      orderByDirectionField: $('#filter-order-by-direction'),
      searchSerieField: $('#search-serie-field'),

      resetSearchSerieButton: $('#reset-search-button'),

      currentPageNumber: 1,
      currentPerPageNumber: 1,
      currentOrderByColumn: '',
      currentOrderByDirection: '',
      currentSerieSearchValue: '',

      init : function(){
        window.odealire.page.updateFiltersCurrentValue();

        // Author search field
        window.odealire.page.resetSearchSerieButton.on('click', function(e){
          window.odealire.page.searchSerieField.val('');
        });

        // Filters form
        $('#filters-form').on('submit', function(e){
          e.preventDefault();
          window.odealire.page.updateFiltersCurrentValue();
          window.odealire.page.updateSeriesList(
            window.odealire.page.currentPerPageNumber,
            1,
            window.odealire.page.currentOrderByColumn,
            window.odealire.page.currentOrderByDirection,
            window.odealire.page.currentSerieSearchValue);
        });

        // list pagination
        window.odealire.page.seriesListContainer.on('click', '.pagination button:not(.active)', function(e){
              window.odealire.page.changeListPage($(this).data('page'));
        });

        // Filters
        $('#apply-filter-button').on('click', function(){
           window.odealire.page.updateFiltersCurrentValue();
           window.odealire.page.updateSeriesList(
             window.odealire.page.currentPerPageNumber,
             1,
             window.odealire.page.currentOrderByColumn,
             window.odealire.page.currentOrderByDirection,
             window.odealire.page.currentSerieSearchValue)
        });

        // Add author form
        $('#add-serie-modal').on('shown.bs.modal', function () {
          $('#add-serie-form-name').focus()
        });

        $('#add-serie-form').on('submit', function(e){
            e.preventDefault();
             var $this = $(this);

             var data = $this.serializeArray();
             data.push({name:'maxNumber', value:window.odealire.page.currentPerPageNumber});
             data.push({name:'pageNumber', value:window.odealire.page.currentPageNumber});
             data.push({name:'orderByColumn', value:window.odealire.page.currentOrderByColumn});
             data.push({name:'orderByDirection', value:window.odealire.page.currentOrderByDirection});
             data.push({name:'search', value:window.odealire.page.currentSerieSearchValue});

             $('#add-serie-form :input').attr("disabled", "disabled");
             window.odealire.ajaxPost(
               $this.attr('action'),
               data,
               'Création',
               'La série a été ajoutée.',
               'Impossible d\'ajouter cette série.',
               function(json){
                     $('#add-serie-modal').modal('hide');
                     $this[0].reset();
                     $('#add-serie-form :input').removeAttr("disabled");
                     window.odealire.page.updateSeriesList(
                       window.odealire.page.currentPerPageNumber,
                       window.odealire.page.currentPageNumber,
                       window.odealire.page.currentOrderByColumn,
                       window.odealire.page.currentOrderByDirection,
                       window.odealire.page.currentSerieSearchValue);
                   },
                function(errorMessage){
                  $('#add-serie-form-error').html(errorMessage);
                  $('#add-serie-form-error').removeClass('hide');
                  $('#add-serie-form :input').removeAttr("disabled");
                }
             );
            });

        // modify dialog
        window.odealire.page.seriesListContainer.on('click', '.btn-modify-serie', function(e){
          window.odealire.page.getSerieModifyForm($(this).closest('tr').data('id'))
        });

        // delete button
        window.odealire.page.seriesListContainer.on('click','.btn-delete-serie', function(e){
            window.odealire.page.deleteAuthor($(this).closest('tr'));
        });

        // modify form
        $('body').on('submit','#modify-serie-form', function(e){
            e.preventDefault();
             var $this = $(this);

             var data = $this.serializeArray();
             data.push({name:'maxNumber', value:window.odealire.page.currentPerPageNumber});
             data.push({name:'pageNumber', value:window.odealire.page.currentPageNumber});
             data.push({name:'orderByColumn', value:window.odealire.page.currentOrderByColumn});
             data.push({name:'orderByDirection', value:window.odealire.page.currentOrderByDirection});
             data.push({name:'search', value:window.odealire.page.currentSerieSearchValue});

             $('#modify-serie-form :input').attr("disabled", "disabled");
             window.odealire.ajaxPost(
               $this.attr('action'),
               data,
               'Modification',
               'La série a été modifiée.',
               'Impossible de modifier cette série.',
               function(json){
                     $('#modify-serie-modal').modal('hide');
                     $this[0].reset();
                     $('#modify-serie-form :input').removeAttr("disabled");
                     $('#modify-serie-modal').modal('hide');

                     window.odealire.page.updateSeriesList(
                       window.odealire.page.currentPerPageNumber,
                       window.odealire.page.currentPageNumber,
                       window.odealire.page.currentOrderByColumn,
                       window.odealire.page.currentOrderByDirection,
                       window.odealire.page.currentSerieSearchValue);
                   },
                function(errorMessage){
                  $('#modify-serie-form-error').html(errorMessage);
                  $('#modify-serie-form-error').removeClass('hide');
                  $('#modify-serie-form :input').removeAttr("disabled");

                  $('#modify-serie-modal').modal('hide');
                }
             );
        });
      },

      // Take filters values and store it
      updateFiltersCurrentValue: function(){
        window.odealire.page.currentPerPageNumber = window.odealire.page.perPageNumberField.val();
        window.odealire.page.currentOrderByColumn = window.odealire.page.orderByColumnField.val();
        window.odealire.page.currentOrderByDirection = window.odealire.page.orderByDirectionField.val();
        window.odealire.page.currentSerieSearchValue = window.odealire.page.searchSerieField.val();
      },

      // Change pagination current page and update data
      changeListPage: function(pageNumber){
        window.odealire.page.updateSeriesList(
          window.odealire.page.currentPerPageNumber,
          pageNumber,
          window.odealire.page.currentOrderByColumn,
          window.odealire.page.currentOrderByDirection,
          window.odealire.page.currentSerieSearchValue);
      },

      // Update series list according to parameters
      updateSeriesList: function(
          maxNumber,
          pageNumber,
          orderByColumn,
          orderByDirection,
          currentSearch){
        window.odealire.ajaxPost(
          window.odealire.page.seriesListDataSource,
          { maxNumber: maxNumber,
            pageNumber: pageNumber,
            orderByColumn: orderByColumn,
            orderByDirection: orderByDirection,
            search: currentSearch },
          null,
          null,
          null,
          function(json){
            window.odealire.page.seriesListContainer.html(json.view);
          });
      },

      getSerieModifyForm: function(serieId){
        // Use ajax to get modify form
        window.odealire.ajaxPost(
          window.odealire.page.getModifyFormUrl,
          { serieId: serieId },
          null,
          null,
          null,
          function(json){
              $('#modify-form-modal-container').html(json.view);
              $('#modify-serie-modal').on('hidden.bs.modal', function (e) {
                $('#modify-form-modal-container').html('');
              });
              $('#modify-serie-modal').on('shown.bs.modal', function () {
                $('#modify-serie-form-name').focus()
              });

              $('#modify-serie-modal').modal('show');
            },
          function(error){

          });
      },

      // Ask confirmation and delete an serie
      deleteAuthor: function(jqRow){
        var serieName = jqRow.data('title');

        window.odealire.confirmDialog(
          'Suppression d\' une série',
          'Voulez-vous réellement supprimmer <i>' + serieName + '</i> de la base de données ?',
          {
            icon: 'fa-times',
            confirmCallback: function(){
                window.odealire.ajaxPost(
                  window.odealire.page.deleteSerieUrl,
                  {
                    maxNumber: window.odealire.page.currentPerPageNumber,
                    pageNumber: window.odealire.page.currentPageNumber,
                    orderByColumn: window.odealire.page.currentOrderByColumn,
                    orderByDirection: window.odealire.page.currentOrderByDirection,
                    search: window.odealire.page.currentSearch,
                    serieId: jqRow.data('id')
                  },
                  'Suppression',
                  serieName + ' a été supprimée.',
                  'Impossible de supprimer la série ' + serieName,
                  function(json){
                      jqRow.remove();
                  },
                  function(){
                  }
                );
            },
        });
    }
}
})();
