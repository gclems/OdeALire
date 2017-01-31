(function(){
    window.odealire.page = {
      // main form fields
      titleField: $('#add-book-form-title'),
      isbnField: $('#add-book-form-isbn'),
      serieSelect: $('#add-book-form-serieId'),
      serieNumberField: $('#add-book-form-serieNumber'),
      descriptionField: $('#add-book-form-description'),
      editorsSelect: $('#add-book-form-editorId'),
      authorsList: $('#book-authors-list'),

      // Editor management
      addEditorButton: $('#btn-add-new-editor'),
      addEditorUrl: $('#btn-add-new-editor').data('url'),
      updateEditorButton: $('#btnUpdateEditors'),
      updateEditorUrl: $('#btnUpdateEditors').data('url'),
      newEditorNameProposal: $('#editor-not-found-name'),
      editorNotFoundAlert: $('#alert-editor-not-found'),

      // Serie management
      updateSerieButton: $('#btnUpdateSeries'),
      updateSerieUrl: $('#btnUpdateSeries').data('url'),
      serieNumberSubstractButton: $('#btnSerieNumberSubstract'),
      serieNumberAddButton: $('#btnSerieNumberAdd'),

      // Author management
      updateAuthorsButton: $('#btnUpdateAuthors'),
      updateAuthorsUrl: $('#btnUpdateAuthors').data('url'),
      authorNotFoundAlert: $('#alert-author-not-found'),
      authorNotFoundRowTemplate: $('#author-not-found-template'),
      authorNotFoundList: $('#authors-not-found-list'),
      authorNotFoundNameSelector: '.author-not-found-name',
      createAuthorUrl: $('#alert-author-not-found').data('url'),

      // authors modal fields
      authorsModal: $('#add-author-modal'),
      authorSelect: $('#add-book-form-authorId'),
      newAuthorField: $('#add-new-author-name'),

      isbn: {
        init: function(){}
      },

      init : function(){

        window.odealire.page.isbn.init();
        window.odealire.page.initForm();

        // Init editor management
        window.odealire.page.addEditorButton.click(function(e){
          window.odealire.page.createEditorFromSuggestion();
        });

        window.odealire.page.updateEditorButton.click(function(e){
            window.odealire.page.updateEditorsList();
        });

        // Init serie management
        window.odealire.page.updateSerieButton.click(function(e){
          window.odealire.page.updateSeriesList();
        });

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

        // Init authors management
        window.odealire.page.updateAuthorsButton.click(function(e){
          window.odealire.page.updateAuthorsList();
        });

        $('#btn-add-author').click(function(e){
          if(window.odealire.page.authorSelect.val() == null || window.odealire.page.authorSelect.val() == ''){
            return;
          }

          var selectedOption = window.odealire.page.authorSelect.find('option:selected');
          var id = parseInt(window.odealire.page.authorSelect.val());
          var text = window.odealire.page.authorSelect.find('option:selected').text();

          window.odealire.page.addAuthor(text, id);
          $('#add-author-modal').modal('hide');
        });

        $('body').on('click', '.hide-author-not-found-button', function(e){
          $(this).closest('li').remove();
        });

        $('body').on('click', '.add-author-not-found-button', function(e){
          var li = $(this).closest('li');
          var author = window.odealire.page.createAuthorFromSuggestion(li, function(author){
            window.odealire.page.addAuthor(author.name, author.id);
            window.odealire.page.updateAuthorsList(author.id);
            li.remove();

            if(window.odealire.page.authorNotFoundList.html() === ''){
              window.odealire.page.authorNotFoundAlert.addClass('hide');
            }
          });
        });
      },

      createEditorFromSuggestion: function(){
        var editorName = window.odealire.page.newEditorNameProposal.html();
        window.odealire.ajaxPost(
            window.odealire.page.addEditorUrl,
            { 'editor-name': editorName },
            'Éditeurs',
            editorName + ' a été créé.',
            'Impossible de créer' + editorName + '.',
            function(json){
                window.odealire.page.updateEditorsList(json.view.id);
                window.odealire.page.editorNotFoundAlert.addClass('hide');
            },
            null
        );
      },

      updateEditorsList: function(idToSelect){
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

      createAuthorFromSuggestion: function(jqLi, successCallback){
        var authorName = jqLi.find(window.odealire.page.authorNotFoundNameSelector).text();
        window.odealire.ajaxPost(
          window.odealire.page.createAuthorUrl,
          { 'author-name' : authorName },
          'Auteurs',
          authorName + " a été créé",
          'Impossible de créer ' + authorName + '.',
          function(json){
            successCallback(json.view);
          },
          null
        );
      },

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
            window.odealire.page.authorSelect.val(idToSelect);
          },
          null
        );
      },

      addAuthor: function(name, id){
          if(name === undefined || name === null || name === ''
          || id === undefined || id === null || !Number.isInteger(id)
          || window.odealire.page.getAddedAuthorsIds().includes(id)){
              return;
          }

          var li = $('<li></li>');
          var deleteButton = $('<button><i class="fa fa-times"></i></button>');

          deleteButton.attr('type','button');
          deleteButton.css('margin-left','10px');
          deleteButton.addClass('btn btn-danger btn-xs');
          deleteButton.click(function(e){ li.remove(); });

          // add author
          if(id === undefined || id === null){
              li.data('id', '');
              li.append('<i class="fa fa-plus"></i>&nbsp;');
          }
          else{
              li.data('id', id);
          }

          li.data('name', name);
          li.append(name);
          li.append(deleteButton);
          window.odealire.page.authorsList.append(li);
      },

      getAddedAuthorsIds: function(){
        var ids = Array();

        $.each(window.odealire.page.authorsList.children('li'), function(index, item){
          ids.push(parseInt($(item).data('id')));
        });

        return ids;
      },

      initForm: function(){
        $('#book-form').on('submit', function(e){
         e.preventDefault();

         // Create authors data
         var authorsArray = [];
         $.each(window.odealire.page.authorsList.find('li'), function(i, li){
             $li = $(li);
             authorsArray.push($li.data('id'));
         });

         // Create data
         var data = {
             bookIsbn: window.odealire.page.isbnField.val(),
             bookTitle: window.odealire.page.titleField.val(),
             bookDescription: window.odealire.page.descriptionField.val(),
             bookEditorId: window.odealire.page.editorsSelect.val(),
             bookSerieId: window.odealire.page.serieSelect.val(),
             bookSerieNumber: window.odealire.page.serieNumberField.val(),
             bookAuthors: JSON.stringify(authorsArray)
         };
        var url =  $(this).attr('action');
        window.odealire.ajaxPost(
             url,
             data,
             null,
             null,
             null,
             function(json){
               window.location.href = $('#btnGoBack').attr('href');
             },
             null
         );
         });
      }
  }
})();
