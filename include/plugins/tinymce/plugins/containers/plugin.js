/*
 *
 * Container TinyMCE plug-in is written by Shakeel Ahmed 2017-2018
 *
 */
tinymce.PluginManager.add('containers', function(editor/*, url*/) {
    editor.addButton('containers', {
        type: 'menubutton',
        image: tinymce.baseURL+'/plugins/containers/containers.png',
        tooltip:'Containers',
        menu:
            [
                {
                    text: 'Standard',
                    onclick: function()
                    {
                        editor.insertContent('<div class="container art-pad-top art-pad-bottom">[add-columns-here]</div>[container-end]');
                    }
                },
                {
                    text: 'Full Width',
                    onclick: function()
                    {
                        editor.insertContent('<div class="container-fluid art-pad-top art-pad-bottom">[add-columns-here]</div>[container-end]');
                    }
                },
                {
                    text: 'Advance',
                    onclick: function()
                    {
                        editor.windowManager.open({
                            title: 'Advance',
                            body:
                                [
                                    {
                                        name   : 'type',
                                        type   : 'listbox',
                                        label  : 'Type',
                                        size   : 40,
                                        autofocus: true,
                                        values :
                                            [
                                                { text: 'Standard',  value: 'container', selected: true},
                                                { text: 'Full width', value: 'container-fluid'}
                                            ]
                                    },
                                    {
                                        name   : 'textcolor',
                                        type   : 'listbox',
                                        label  : 'Body Text Color',
                                        size   : 40,
                                        values :
                                            [
                                                { text: 'Default', value: '', selected: true},
                                                { text: 'Black',    value: 'art-text-black'},
                                                { text: 'White',    value: 'art-text-white'},
                                                { text: 'Red',      value: 'art-text-red'},
                                                { text: 'Green',    value: 'art-text-green'},
                                                { text: 'Blue',     value: 'art-text-blue'},
                                                { text: 'Yellow',   value: 'art-text-yellow'},
                                                { text: 'Purple',   value: 'art-text-purple'},
                                                { text: 'Orange',   value: 'art-text-orange'},
                                                { text: 'Gray',     value: 'art-text-gray'},
                                                { text: 'Dark Gray',value: 'art-text-dark-gray'}
                                            ]
                                    },
                                    {
                                        name   : 'bgcolor',
                                        type   : 'listbox',
                                        label  : 'Background Color',
                                        size   : 40,
                                        values :
                                            [
                                                { text: 'Default',  value: '', selected: true},
                                                { text: 'Black',    value: 'art-background-black'},
                                                { text: 'White',    value: 'art-background-white'},
                                                { text: 'Red',      value: 'art-background-red'},
                                                { text: 'Green',    value: 'art-background-green'},
                                                { text: 'Blue',     value: 'art-background-blue'},
                                                { text: 'Yellow',   value: 'art-background-yellow'},
                                                { text: 'Purple',   value: 'art-background-purple'},
                                                { text: 'Orange',   value: 'art-background-orange'},
                                                { text: 'Gray',     value: 'art-background-gray'},
                                                { text: 'Dark Gray',value: 'art-background-dark-gray'}
                                            ]
                                    },
                                    {
                                        name     : 'bgimage',
                                        type     : 'filepicker',
                                        label    : 'Background Image',
                                        size     : 40,
                                    },
                                    {
                                        name    : 'overlay',
                                        type    : 'listbox',
                                        label   : 'Dimming',
                                        values :
                                            [
                                                { text: 'None',     value: '', selected: true},
                                                { text: 'Black',    value: 'art-overlay-black'},
                                                { text: 'White',    value: 'art-overlay-white'},
                                                { text: 'Red',      value: 'art-overlay-red'},
                                                { text: 'Green',    value: 'art-overlay-green'},
                                                { text: 'Blue',     value: 'art-overlay-blue'},
                                                { text: 'Yellow',   value: 'art-overlay-yellow'},
                                                { text: 'Purple',   value: 'art-overlay-purple'},
                                                { text: 'Orange',   value: 'art-overlay-orange'},
                                                { text: 'Gray',     value: 'art-overlay-gray'},
                                                { text: 'Dark Gray',value: 'art-overlay-dark-gray'}
                                            ]
                                    },
                                    {
                                        name    : 'attachment',
                                        type    : 'listbox',
                                        label   : 'Background Attachment',
                                        values :
                                            [
                                                { text: 'Scroll',     value: '', selected: true},
                                                { text: 'Fix',    value: 'art-background-fix'},
                                            ]
                                    },
                                ],
                            onsubmit: function(e)
                            {
                                if(e.data.bgimage != '') e.data.bgimage = ' style="background-image : url('+e.data.bgimage+');" ';
                                editor.insertContent
                                (
                                    '<div class="'+e.data.bgcolor+' '+e.data.textcolor+' '+e.data.attachment+' art-image-container"'+e.data.bgimage+'>' +
                                    '<div class="'+e.data.overlay+' art-pad-top art-pad-bottom">'+
                                    '<div class="'+e.data.type+'">'+
                                    '[add-columns-here]' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>[container-end]'
                                );
                            }
                        });
                    }
                },
            ]
    });
});
