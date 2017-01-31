(function(){
    window.odealire.page = {
      // main form fields
      idField: $('#edit-book-id'),
      titleField: $('#edit-book-form-title'),
      isbnField: $('#edit-book-form-isbn'),
      serieSelect: $('#edit-book-form-serieId'),
      serieNumberField: $('#edit-book-form-serieNumber'),
      descriptionField: $('#edit-book-form-description'),
      editorsSelect: $('#edit-book-form-editorId'),
      authorsList: $('#book-authors-list'),

      updateEditorButton: $('#btnUpdateEditors'),
      updateEditorUrl: $('#btnUpdateEditors').data('url'),

      updateSerieButton: $('#btnUpdateSeries'),
      updateSerieUrl: $('#btnUpdateSeries').data('url'),

      authorsModal: $('#add-author-modal'),
      updateAuthorsButton: $('#btnUpdateAuthors'),
      updateAuthorsUrl: $('#btnUpdateAuthors').data('url'),
      authorSelect: $('#add-book-form-authorId'),

      init : function(){
         window.odealire.page.initForm();

        var authorsJson = $('#book-authors-source').html();
        var json = JSON.parse(authorsJson);
        if(json.length > 0){
          for(var i=0; i < json.length; i++){
            window.odealire.page.addAuthor(json[i].Name, json[i].Id);
          }
        };

        window.odealire.page.updateEditorButton.click(function(e){
            window.odealire.page.updateEditorsList();
        });

        window.odealire.page.updateSerieButton.click(function(e){
          window.odealire.page.updateSeriesList();
        });

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
          window.odealire.page.authorsModal.modal('hide');
        });
      },

      initForm: function(){
          $('#book-form').on('submit', function(e){
             e.preventDefault();

             // create authors data
             var authorsArray = [];
             $.each(window.odealire.page.authorsList.find('li'), function(i, li){
                 $li = $(li);
                 authorsArray.push($li.data('id'));
             });

             // create data
             var data = {
                 bookId: window.odealire.page.idField.val(),
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


    }
})();
