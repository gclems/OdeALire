(function(){
    window.odealire.page = {

      // Serie management
      updateSerieButton: $('#btnUpdateSeries'),
      updateSerieUrl: $('#btnUpdateSeries').data('url'),
      serieSelect: $('#add-book-form-serieId'),
      serieNumberField: $('#add-book-form-serieNumber'),
      serieNumberSubstractButton: $('#btnSerieNumberSubstract'),
      serieNumberAddButton: $('#btnSerieNumberAdd'),

      // Publisher management
      addEditorButton: $('#btn-add-new-editor'),
      addEditorUrl: $('#btn-add-new-editor').data('url'),
      updateEditorButton: $('#btnUpdateEditors'),
      updateEditorUrl: $('#btnUpdateEditors').data('url'),
      editorsSelect: $('#add-book-form-editorId'),
      unknownPublisher: $('#editor-not-found-name'),
      editorNotFoundAlert: $('#alert-editor-not-found'),
      hideUnknownPublisherButton: $('#btn-publisher-name-remove'),

      // Authors management
      updateAuthorsButton: $('#btnUpdateAuthors'),
      updateAuthorsUrl: $('#btnUpdateAuthors').data('url'),
      addAuthorButton: $('#btn-add-author'),
      authorsList: $('#book-authors-list'),
      authorSelect: $('#add-book-form-authorId'),
      unknownAuthorsAlert: $('#alert-author-not-found'),
      unknownAuthorsList: $('#authors-not-found-list'),
      hideUnkownAuthorButtonsSelector: '.hide-author-not-found-button',
      removeAuthorButtonsSelector: '.btn-remove-author',

      init: function(){
        // Init update buttons
        window.odealire.page.updateEditorButton.click(function(e){
            window.odealire.page.updatePublishersList();
        });

        window.odealire.page.updateSerieButton.click(function(e){
          window.odealire.page.updateSeriesList();
        });

        window.odealire.page.updateAuthorsButton.click(function(e){
          window.odealire.page.updateAuthorsList();
        });

        // Serie management
        window.odealire.page.serieSelect.change(function(e){
          if($(this).val() === ''){
            window.odealire.page.serieNumberSubstractButton.attr('disabled', 'disabled');
            window.odealire.page.serieNumberAddButton.attr('disabled', 'disabled');
            window.odealire.page.serieNumberField.attr('disabled', 'disabled');
          }
          else{
            window.odealire.page.serieNumberSubstractButton.removeAttr('disabled');
            window.odealire.page.serieNumberAddButton.removeAttr('disabled');
            window.odealire.page.serieNumberField.removeAttr('disabled');
          }
        });

        // Publisher management
        window.odealire.page.addEditorButton.click(function(e){
          window.odealire.page.createEditorFromSuggestion();
        });

        window.odealire.page.hideUnknownPublisherButton.click(function(e){
          window.odealire.page.editorNotFoundAlert.remove();
        });

        // Authors management
        $('body').on('click', window.odealire.page.removeAuthorButtonsSelector, function(e){
          var li = $(this).parents('li');
          if(li != null){
            li.remove();
          }
        });

        $('body').on('click', window.odealire.page.hideUnkownAuthorButtonsSelector, function(e){
          var li = $(this).parents('li');
          if(li != null){
            li.remove();

            if(window.odealire.page.unknownAuthorsList.children('li').length == 0){
              window.odealire.page.unknownAuthorsAlert.remove();
            }
          }
        });

        window.odealire.page.addAuthorButton.click(function(e){
          if(window.odealire.page.authorSelect.val() == null || window.odealire.page.authorSelect.val() == ''){
            return;
          }

          var selectedOption = window.odealire.page.authorSelect.find('option:selected');
          var id = parseInt(window.odealire.page.authorSelect.val());
          var text = selectedOption.text();
          
          var li = $('<li></li>');
          var deleteButton = $('<button><i class="fa fa-times"></i></button>');
          deleteButton.attr('type','button');
          deleteButton.addClass('btn btn-xs btn-danger btn-remove-author');

          li.data('id', id);
          li.data('name', text);
          li.append(text);
          li.append(deleteButton);
          window.odealire.page.authorsList.append(li);

          $('#add-author-modal').modal('hide');
        });
      },

      // Updates publishers list.
      updatePublishersList: function(idToSelect){
          window.odealire.ajaxGet(
               window.odealire.page.updateEditorUrl,
               'Éditeurs',
               'Mise à jour de la liste des effectuée.',
               'Impossible d\'actualiser la liste des éditeurs.',
               function(json){
                   var selected = idToSelect == null ? window.odealire.page.editorsSelect.val() : idToSelect;

                   var options = '<option value=""></option>';
                   $.each(json.view, function(index, item){
                       options += '<option value="' + item.id + '">'
                               + item.name + '</option>';
                   });

                   window.odealire.page.editorsSelect.html(options);
                   window.odealire.page.editorsSelect.val(selected);
               },
               null
           );
      },

      // Calls server to create new publisher
      createEditorFromSuggestion: function(){
        var editorName = window.odealire.page.unknownPublisher.html();
        window.odealire.ajaxPost(
            window.odealire.page.addEditorUrl,
            { 'editor-name': editorName },
            'Éditeurs',
            editorName + ' a été créé.',
            'Impossible de créer' + editorName + '.',
            function(json){
                window.odealire.page.updatePublishersList(json.view.id);
                window.odealire.page.editorNotFoundAlert.remove();
            },
            null
        );
      },

      // Updates series list.
      updateSeriesList: function(){
        window.odealire.ajaxGet(
          window.odealire.page.updateSerieUrl,
          'Séries',
          'Mise à jour de la liste effectuée.',
          'Impossible d\'actualiser la liste des séries.',
          function(json){
            var selected = window.odealire.page.serieSelect.val();

            var options = '<option value="">Aucune</option>'
            $.each(json.view, function(index, item){
              options += '<option value="' + item.id + '" '
                      + (item.id == selected ? ' selected="selected"' : '') + '>'
                      + item.title + '</option>';
            });

            window.odealire.page.serieSelect.html(options);
          },
          null
        );
      },

       // Updates authors list.
      updateAuthorsList: function(idToSelect){
        window.odealire.ajaxGet(
          window.odealire.page.updateAuthorsUrl,
          'Auteurs',
          'Mise à jour de la liste effectuée.',
          'Impossible d\'actualiser la liste des auteurs.',
          function(json){
            var selected = idToSelect == null ? window.odealire.page.authorSelect.val() : idToSelect;

            var options = ''
            $.each(json.view, function(index, item){
              options += '<option value="' + item.id + '">'
                      + item.name + '</option>';
            });

            window.odealire.page.authorSelect.html(options);
            window.odealire.page.authorSelect.val(selected);
          },
          null
        );
      },
}})();
