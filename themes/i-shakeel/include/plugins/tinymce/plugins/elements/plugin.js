/*
 *
 * Elements TinyMCE plug-in is written by Shakeel Ahmed 2017-2018
 *
 */
tinymce.PluginManager.add('elements', function(editor /*, url*/) {
    // Add a button that opens a window
    editor.addButton('elements', {
        type: 'menubutton',
        image: tinymce.baseURL+'/plugins/elements/elements.png',
        tooltip:'Elements',
        menu:
            [
                {
                    text: 'Jumbotron',
                    onclick: function()
                    {
                        editor.windowManager.open({
                            title: 'Jumbotron',
                            body: [
                                {
                                    name    : 'title',
                                    type    : 'textbox',
                                    label   : 'Title',
                                    size    : 40,
                                    autofocus: true
                                },
                                {
                                    name    : 'caption',
                                    type    : 'textbox',
                                    label   : 'Caption',
                                    size    : 40
                                },
                                {
                                    name   : 'jumbotronType',
                                    type   : 'listbox',
                                    label  : 'Type',
                                    values :
                                        [
                                            { text: 'Standard',     value: 'art-jumbotron', selected: true},
                                            { text: 'Fullscreen',   value: 'art-jumbotron-fullscreen'},
                                            { text: 'Title Block',   value: 'art-jumbotron-title-block'},
                                        ]
                                },
                                {
                                    name    : 'jumbotronImage',
                                    type    : 'filepicker',
                                    filetype: 'file',
                                    size    : 40,
                                    label   : 'Image'
                                },
                                {
                                    name    : 'attachment',
                                    type    : 'listbox',
                                    label   : 'Image Attachment',
                                    values :
                                        [
                                            { text: 'Scroll',     value: '', selected: true},
                                            { text: 'Fix',    value: 'art-background-fix'},
                                        ]
                                },
                                {
                                    name   : 'bgcolor',
                                    type   : 'listbox',
                                    label  : 'Background Color',
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
                                    name   : 'textcolor',
                                    type   : 'listbox',
                                    label  : 'Text Color',
                                    values :
                                        [
                                            { text: 'Default',  value: '', selected: true},
                                            { text: 'Black',    value: 'art-text-black'},
                                            { text: 'White',    value: 'art-text-white'},
                                            { text: 'Red',      value: 'art-text-red'},
                                            { text: 'Green',    value: 'art-text-green'},
                                            { text: 'Blue',     value: 'art-text-blue'},
                                            { text: 'Yellow',   value: 'art-text-yellow'},
                                            { text: 'Purple',   value: 'art-text-purple'},
                                            { text: 'Orange',   value: 'art-background-orange'},
                                            { text: 'Gray',     value: 'art-background-gray'},
                                            { text: 'Dark Gray',value: 'art-background-dark-gray'}
                                        ]
                                },
                                {
                                    name    : 'overlay',
                                    type    : 'listbox',
                                    label   : 'Dimming',
                                    values :
                                        [
                                            { text: 'None',     value: '', selected: true},
                                            { text: 'Black',    value: 'art-overlay-black art-jumbotron-base'},
                                            { text: 'White',    value: 'art-overlay-white art-jumbotron-base'},
                                            { text: 'Red',      value: 'art-overlay-red art-jumbotron-base'},
                                            { text: 'Green',    value: 'art-overlay-green art-jumbotron-base'},
                                            { text: 'Blue',     value: 'art-overlay-blue art-jumbotron-base'},
                                            { text: 'Yellow',   value: 'art-overlay-yellow art-jumbotron-base'},
                                            { text: 'Purple',   value: 'art-overlay-purple art-jumbotron-base'},
                                            { text: 'Orange',   value: 'art-overlay-orange art-jumbotron-base'},
                                            { text: 'Gray',     value: 'art-overlay-gray art-jumbotron-base'},
                                            { text: 'Dark Gray',value: 'art-overlay-dark-gray art-jumbotron-base'}
                                        ]
                                },
                            ],
                            onsubmit: function(e)
                            {
                                if(e.data.overlay=='') e.data.overlay = 'art-jumbotron-base';
                                editor.insertContent
                                (
                                    '<div class="'+e.data.jumbotronType+' '+e.data.bgcolor+' '+e.data.attachment+'" style="background-image:url('+e.data.jumbotronImage+');">'+
                                    '<div class="'+e.data.overlay+' '+e.data.textcolor+'">'+
                                    '<h1>'+e.data.title+'</h1>'+
                                    '<h3>'+e.data.caption+'</h3>'+
                                    '</div>'+
                                    '</div>[jumbotron-end]'
                                );
                            }
                        });
                    }
                },
                {
                    text: 'Fluid Image',
                    onclick: function()
                    {
                        editor.windowManager.open({
                            title: 'Fluid Image Picker',
                            body: [
                                {
                                    name    : 'fluidimage',
                                    type    : 'filepicker',
                                    filetype: 'file',
                                    size    : 40,
                                    autofocus: true,
                                    label   : 'Image'
                                }
                            ],
                            onsubmit: function(e)
                            {
                                if(e.data.fluidimage!='')
                                {
                                    editor.insertContent
                                    (
                                        '<img class="art-fluid-image" src="' + e.data.fluidimage + '">'
                                    );
                                }
                                else
                                {
                                    editor.windowManager.alert('No image has been selected');
                                    return false;
                                }
                            }
                        });
                    }
                },
/*
                {
                    text: 'Page',
                    onclick: function()
                    {
                        editor.windowManager.open({
                            title: 'Article',
                            body: [
                                {
                                    name    : 'article',
                                    type    : 'filepicker',
                                    filetype: 'file',
                                    size    : 40,
                                    autofocus: true,
                                    label   : 'Pick Artilce'
                                },
                                {
                                    name   : 'ctype',
                                    type   : 'listbox',
                                    label  : 'Style',
                                    values :
                                        [
                                            { text: 'Default', value: 'card', selected: true},
                                            { text: 'Default Compact', value: 'card-compact'},
                                            { text: 'Borderless', value: 'card-borderless'},
                                            { text: 'Borderless Compact', value: 'card-borderless-compact'},
                                            { text: 'Pic + Title', value: 'card-picture-plus-title'},
                                            { text: 'Pic + Title Compact', value: 'card-picture-plus-title-compact'},
                                            { text: 'Shadow Card', value: 'card-2'},
                                            { text: 'Shadow Card Compact', value: 'card-2-compact'},
                                            { text: 'Black Border', value: 'card-3'},
                                            { text: 'Black Border Compact', value: 'card-3-compact'},
                                            { text: 'Title + Description', value: 'text-only' }
                                        ]
                                },
                            ],
                            onsubmit: function(e)
                            {
                                if(e.data.article!='')
                                {
                                    $.get(e.data.article + e.data.ctype, '', function (data, status)
                                    {
                                        editor.insertContent(data);
                                    });
                                }
                                else
                                {
                                    editor.windowManager.alert('Please pick article to continue.');
                                    return false;
                                }
                            }
                        });
                    }
                },
*/
                {
                    text: 'Button',
                    onclick: function()
                    {
                        editor.windowManager.open({
                            title: 'Insert Button',
                            body: [
                                {
                                    name    : 'label',
                                    type    : 'textbox',
                                    value   : 'Button',
                                    label   : 'Label',
                                    size    : 40,
                                    autofocus: true
                                },
                                {
                                    name    : 'link',
                                    type    : 'filepicker',
                                    filetype: 'file',
                                    size    : 40,
                                    label   : 'Link'
                                },
                                {
                                    name    : 'icon',
                                    type    : 'filepicker',
                                    label   : 'Icon',
                                    size    : 40,
                                    autofocus: true
                                },
                                {
                                    name   : 'textcolor',
                                    type   : 'listbox',
                                    label  : 'Text Color',
                                    values :
                                        [
                                            { text: 'Black',    value: 'art-text-black', selected: true},
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
                                    name   : 'backgroundcolor',
                                    type   : 'listbox',
                                    label  : 'Color',
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
                                }
                            ],
                            onsubmit: function(e) {
                                if(e.data.label=='') e.data.label = 'Button';
                                var buttonData = '<button class="art-button '+e.data.backgroundcolor+' '+e.data.textcolor+' center cross-center">'+e.data.label+' <span class="art-icon">'+e.data.icon+'</span></button>';
                                if (e.data.link == '') editor.insertContent (buttonData);
                                else editor.insertContent ('<a href="'+e.data.link+'">'+buttonData+'</a>');
                            }
                        });
                    }
                },
                {
                    text: 'Deco Border',
                    onclick: function ()
                    {
                        editor.windowManager.open({
                            title: 'Deco Borders',
                            size: 40,
                            body:
                                [
                                    {
                                        name   : 'style',
                                        type   : 'listbox',
                                        label  : 'Style',
                                        size   : 40,
                                        values :
                                            [
                                                { text: 'Ornamental 1',    value: 'art-deco-border-ornamental-1',selected:true},
                                                { text: 'Ornamental 2',    value: 'art-deco-border-ornamental-2'},
                                                { text: 'Ornamental 3',    value: 'art-deco-border-ornamental-3'},
                                                { text: 'Ornamental 4',    value: 'art-deco-border-ornamental-4'},
                                                { text: 'Sci-Fi 1',        value: 'art-deco-border-sci-fi-1'},
                                                { text: 'Sci-Fi 2',        value: 'art-deco-border-sci-fi-2'},
                                                { text: 'Sci-Fi 3',        value: 'art-deco-border-sci-fi-3'},
                                                { text: 'Roman 1',         value: 'art-deco-border-roman-1'},
                                            ]
                                    },
                                    {
                                        name   : 'bgcolor',
                                        type   : 'listbox',
                                        label  : 'Background Color',
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
                                        name    : 'size',
                                        type    : 'textbox',
                                        label   : 'Size',
                                        size    : 40,
                                        value   : 36
                                    },
                                ],
                            onsubmit: function(e)
                            {
                                editor.insertContent ('<div class="art-deco-border '+ e.data.bgcolor +'" style="background-image: url(\'../../../../images/'+ e.data.style +'.svg\'); height:'+ e.data.size +'px;"></div>[deco-border-end]');
                            }

                        })
                    }
                },
                {
                    text: 'Icon',
                    onclick: function()
                    {
                        editor.windowManager.open({
                            title: 'Icon',
                            body: [
                                {
                                    name    : 'icon',
                                    type    : 'filepicker',
                                    label   : 'Icon',
                                    size    : 40,
                                    autofocus: true
                                },
                                {
                                    name   : 'iconcolor',
                                    type   : 'listbox',
                                    label  : 'Icon Color',
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
                                    name    : 'href',
                                    type    : 'filepicker',
                                    filetype: 'file',
                                    size    : 40,
                                    label   : 'Link'
                                },
                            ],
                            onsubmit: function(e) {
                                if(e.data.icon != '')
                                {
                                    if(e.data.href=='') editor.insertContent('<span class="art-icon '+e.data.iconcolor+'">'+e.data.icon+'</span>');
                                    else editor.insertContent('<a href="'+ e.data.href +'"><span class="art-icon '+e.data.iconcolor+'">'+e.data.icon+'</span></a>');
                                }
                                else editor.windowManager.alert('No icon is selected, nothing to add');
                            }
                        });
                    }
                },
                {
                    text: 'Marker',
                    onclick: function()
                    {
                        editor.windowManager.open({
                            title: 'Marker',
                            body: [
                                {
                                    name    : 'mtext',
                                    type    : 'textbox',
                                    label   : 'Text',
                                    value   : editor.selection.getContent(),
                                    size    : 40,
                                },
                                {
                                    name   : 'txcolor',
                                    type   : 'listbox',
                                    label  : 'Text Color',
                                    size    : 40,
                                    values :
                                        [
                                            { text: 'None',     value: '', selected: true},
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
                                    label  : 'Marker Color',
                                    size   : 40,
                                    values :
                                        [
                                            { text: 'Black',    value: 'art-background-black', selected: true},
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
                                    name   : 'size',
                                    type   : 'listbox',
                                    label  : 'Marker Size',
                                    size   : 40,
                                    values :
                                        [
                                            { text: 'Normal',     value: 'art-marker', selected: true},
                                            { text: 'Large',      value: 'art-marker-large'},
                                            { text: 'Extra Large',value: 'art-marker-extra-large'},
                                        ]
                                }
                            ],
                            onsubmit: function(e)
                            {
                                if(e.data.mtext != '')
                                {
                                    editor.insertContent
                                    (
                                        '<span class="'+e.data.bgcolor+' '+e.data.txcolor+' '+e.data.size+'">' + e.data.mtext + '</span>'
                                    );
                                }
                                else
                                {
                                    editor.windowManager.alert('Please enter text for marking to continue');
                                    return false;
                                }
                            }
                        });
                    }
                },
            ]
    });
});
