/*
 *
 * Container TinyMCE plug-in is written by Shakeel Ahmed 2017-2018
 *
 */
tinymce.PluginManager.add('columns', function(editor/*, url*/) {
    // Add a button that opens a window
    editor.addButton('columns', {
        type: 'menubutton',
        text: false,
        image: tinymce.baseURL+'/plugins/columns/columns.png',
        tooltip:'Columns',
        menu: [
            {
                text: '1 column',
                onclick: function()
                {
                    editor.insertContent
                    (
                        '<div class="row">' +
                        '<div class="col-md-12"><p>column</p></div>' +
                        '</div>[column-end]'
                    );
                }
            },
            {
                text: '2 columns',
                onclick: function()
                {
                    editor.insertContent
                    (
                        '<div class="row">' +
                        '<div class="col-md-6"><p>column 1</p></div>' +
                        '<div class="col-md-6"><p>column 2</p></div>' +
                        '</div>[column-end]'
                    );
                }
            },
            {
                text: '3 columns',
                onclick: function()
                {
                    editor.insertContent
                    (
                        '<div class="row">' +
                        '<div class="col-md-4"><p>column 1</p></div>' +
                        '<div class="col-md-4"><p>column 2</p></div>' +
                        '<div class="col-md-4"><p>column 3</p></div>' +
                        '</div>[column-end]'
                    );
                }
            },
            {
                text: '4 columns',
                onclick: function()
                {
                    editor.insertContent
                    (
                        '<div class="row">' +
                        '<div class="col-md-3"><p>column 1</p></div>' +
                        '<div class="col-md-3"><p>column 2</p></div>' +
                        '<div class="col-md-3"><p>column 3</p></div>' +
                        '<div class="col-md-3"><p>column 4</p></div>' +
                        '</div>[column-end]'
                    );
                }
            },
            {
                text: '6 columns',
                onclick: function()
                {
                    editor.insertContent
                    (
                        '<div class="row">' +
                        '<div class="col-md-2"><p>column 1</p></div>' +
                        '<div class="col-md-2"><p>column 2</p></div>' +
                        '<div class="col-md-2"><p>column 3</p></div>' +
                        '<div class="col-md-2"><p>column 4</p></div>' +
                        '<div class="col-md-2"><p>column 5</p></div>' +
                        '<div class="col-md-2"><p>column 6</p></div>' +
                        '</div>[column-end]'
                    );
                }
            },
            {
                text: '-----------------------',
            },
            {
                text: 'sidebar both side',
                onclick: function()
                {
                    editor.insertContent
                    (
                        '<div class="row">' +
                        '<div class="col-md-3"><p>left sidebar</p></div>' +
                        '<div class="col-md-6"><p>contents</p></div>' +
                        '<div class="col-md-3"><p>right sidebar</p></div>' +
                        '</div>[column-end]'
                    );
                }
            },
            {
                text: 'sidebar right',
                onclick: function()
                {
                    editor.insertContent
                    (
                        '<div class="row">' +
                        '<div class="col-md-8"><p>content</p></div>' +
                        '<div class="col-md-4"><p>right sidebar</p></div>' +
                        '</div>[column-end]'
                    );
                }
            },
            {
                text: 'sidebar left',
                onclick: function()
                {
                    editor.insertContent
                    (
                        '<div class="row">' +
                        '<div class="col-md-4"><p>left sidebar</p></div>' +
                        '<div class="col-md-8"><p>contents</p></div>' +
                        '</div>[column-end]'
                    );
                }
            }
        ]
    });

});
