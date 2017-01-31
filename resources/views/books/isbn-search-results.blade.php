<div
    id="google-warning"
    class="alert alert-warning hide">
    Aucun résultat trouvé chez Google.
</div>
<div
    id="isbndb-warning"
    class="alert alert-warning hide">
    Aucun résultat trouvé chez IsbnDB.
</div>

<div class="row">
    <table
        id="isbn-search-results-table"
        class="table table-hover table-responsive table-bordered">
        <thead>
            <tr>
                <th class="text-right"></th><th class="text-center">Aucun</th><th>Google</th><th>IsbnDB</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="text-right">Titre</th>
                <td class="text-center">
                  <input
                      id="title-none-radio"
                      class="book-info-selector"
                      type="radio"
                      name="title" />
                </td>
                <td>
                    <input
                        id="title-google-radio"
                        class="book-info-selector"
                        type="radio"
                        name="title" />
                    <span id="title-google-text"></span>
                </td>
                <td>
                    <input
                        id="title-isbndb-radio"
                        class="book-info-selector"
                        type="radio"
                        name="title" />
                    <span id="title-isbndb-text"></span>
                </td>
            </tr>
            <tr>
                <th class="text-right">Auteur(s)</th>
                <td class="text-center">
                  <input
                      id="authors-none-radio"
                      class="book-info-selector"
                      type="radio"
                      name="authors" />
                </td>
                <td>
                    <input
                        id="authors-google-radio"
                        class="book-info-selector"
                        type="radio"
                        name="authors" />
                    <ul id="authors-google-list"></ul>
                </td>
                <td>
                    <input
                        id="authors-isbndb-radio"
                        class="book-info-selector"
                        type="radio"
                        name="authors" />
                    <ul id="authors-isbndb-list"></ul>
                </td>
            </tr>
            <tr>
                <th class="text-right">Éditeur</th>
                <td class="text-center">
                  <input
                      id="editor-none-radio"
                      class="book-info-selector"
                      type="radio"
                      name="editor" />
                </td>
                <td>
                    <input
                        id="editor-google-radio"
                        class="book-info-selector"
                        type="radio"
                        name="editor" />
                    <span id="editor-google-text"></span>
                </td>
                <td>
                    <input
                        id="editor-isbndb-radio"
                        class="book-info-selector"
                        type="radio"
                        name="editor" />
                    <span id="editor-isbndb-text"></span>
                </td>
            </tr>
            <tr>
                <th class="text-right">Description</th>
                <td class="text-center">
                  <input
                      id="description-none-radio"
                      class="book-info-selector"
                      type="radio"
                      name="description" />
                </td>
                <td>
                    <input
                        id="description-google-radio"
                        class="book-info-selector"
                        type="radio"
                        name="description" />
                    <div id="description-google-text"></div>
                </td>
                <td>
                    <input
                        id="description-isbndb-radio"
                        class="book-info-selector"
                        type="radio"
                        name="description" />
                    <div id="description-isbndb-text"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
