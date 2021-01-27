<?php


if($mod == 'editor')
{
    $status = ['1'=>'Revision Required','3'=>'Under Review','4'=>'Publish','5'=>'Set as Homepage'];
    switch($row['status'])
    {
        case 1 : {$delete_status = $row['page-id'];} break;
        case 2 : {$delete_status = $row['page-id'];} break;
        case 3 : {$delete_status = $row['page-id'];} break;
        case 4 : {$delete_status = $row['page-id'];} break;
        default : $delete_status = 'locked'; break;
    }
}
if($mod == 'author')
{
    $status = ['2'=>'Unpublish','1'=>'Under progress','3'=>'Send For Review'];
    switch($row['status'])
    {
        case 1 : {$delete_status = $row['page-id'];} break;
        case 2 : {$delete_status = $row['page-id'];} break;
        default : $delete_status = 'locked'; break;
    }
}

?>
<div id="body-contents" class="modal column cross-center" style="display:none;">
    <i class="art-icon hover" style="margin-left: auto; margin-right: 50px; position: absolute; top:20px;right:5px;" onclick="closeModal('body-contents');">cancel</i>
    <label class="space-top">Body HTML Code Editor</label>
    <div id="body-contents-editor" class="ace-editor"><?php echo str_replace($this->config->item('uploadir'),base_url($this->config->item('uploadir')),$row['body']); ?></div>
</div>
<div id="header-contents" class="modal column cross-center" style="display:none; flex-flow: row wrap;">
    <i class="art-icon hover" style="margin-left: auto; margin-right: 50px; position: absolute; top:20px;right:5px;" onclick="closeModal('header-contents');">cancel</i>
    <label class="space-top"><i class="art-icon hover">vertical_align_top</i>Header Code Editor</label>
    <div id="header-contents-editor" class="ace-editor"></div>
</div>
<div id="footer-contents" class="modal cross-center" style="display:none; flex-flow: row wrap;">
    <i class="art-icon hover" style="margin-left: auto; margin-right: 50px; position: absolute; top:20px;right:5px;" onclick="closeModal('footer-contents');">cancel</i>
    <label class="space-top"><i class="art-icon hover">vertical_align_bottom</i>Footer Code Editor</label>
    <div id="footer-contents-editor" class="ace-editor"><?php echo $row['footer']; ?></div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-lg-8 mt-3 mb-3 mt-md-0 mb-md-0">
            <h4 class="text-center text-md-left">Edit Article</h4>
        </div>
        <div class="col-md-6 col-lg-4 cross-center center">
            <div class="menu-icon hover space-right-1" onclick="openModal('header-contents');">
                <div class="art-icon">vertical_align_top</div><div class="menu-icon-text">Header</div>
            </div>
            <div class="menu-icon hover space-right-1" onclick="openModal('footer-contents');">
                <div class="art-icon">vertical_align_bottom</div><div class="menu-icon-text">footer</div>
            </div>
            <div class="menu-icon hover space-right-1" onclick="deleteArticle('<?php echo $delete_status; ?>','<?php echo base_url().$mod; ?>/admin/delete-article/','<?php echo base_url().$mod; ?>/admin/');">
                <div class="art-icon">delete</div><div class="menu-icon-text">delete</div>
            </div>
            <div class="menu-icon hover" onclick="goBack();">
                <div class="art-icon">cancel</div><div class="menu-icon-text">cancel</div>
            </div>
        </div>
    </div>
    <form name="createPage" id="createPage" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 col-lg-8 column spread space-top">
                <div>
                    <label class="mt-3 mt-md-0"><i class="art-icon">account_box</i>Title</label>
                    <input name="page_headings" id="page_headings" class="form" value="<?php echo $row['title']; ?>" required>
                </div>
                <div>
                    <label class="mt-3 mt-md-0"><i class="art-icon">vpn_key</i>Keywords</label>
                    <input name="page_seo_keywords" id="page_seo_keywords" class="form" value="<?php echo $row['keywords']; ?>">
                </div>
                <div>
                    <label class="mt-3 mt-md-0"><i class="art-icon">description</i>Description</label>
                    <input name="page_details" id="page_details" class="form" value="<?php echo $row['description']; ?>">
                </div>
                <div>
                    <label class="mt-3 mt-md-0"><i class="art-icon">description</i>Status</label>
                    <select name="status" class="form">
                        <option value="">Choose</option>
                        <?php foreach ($status as $key => $value) { if($row['status']==$key) $sel = 'selected="selected"'; else $sel = null; ?>
                            <option value="<?php echo $key; ?>" <?php echo $sel; ?>><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 space-top">
                <label><i class="art-icon">image</i>Page Image</label>
                <div id="picBox" class="picture-box" onclick="triggerField('#page_title_picture');" style="background-image: url('<?php echo $row['image'] ; ?>');">
                    <span class="icon-small sa-cancel" onclick="cancel_upload();"></span>
                </div>
            </div>
            <div class="col-md-12 space-top">
                <label class="cross-center"><i class="art-icon">computer</i> Body Contents <span class="cross-center hover" onclick="openModal('body-contents');" style="margin-left: auto;">Code Editor <i class="art-icon">code</i></span></label>
                <textarea name="page_contents" id="page_contents"><?php echo str_replace($this->config->item('uploadir'),base_url($this->config->item('uploadir')),$row['body']); ?></textarea>
            </div>
        </div>
        <div class="row space-top">
            <div class="col-12 center">
                <input type="submit" class="button hallow" value="Save">
                <textarea name="page_header_contents" id="page_header_contents" style="width:0px;height:0px;opacity:0;"><?php echo $row['header']; ?></textarea>
                <textarea name="page_footer_contents" id="page_footer_contents" style="width:0px;height:0px;opacity:0;"><?php echo $row['footer']; ?></textarea>
                <input type="file" name="page_title_picture" id="page_title_picture" accept="image/*" onchange="show(event,'picBox');" style="display: none;">
                <input type="hidden" name="current_title_picture" value="<?php echo basename($row['image']); ?>">
                <input type="hidden" name="current-title"  value="<?php echo dash_url($row['title']); ?>">
                <input type="hidden" name="current-status" value="<?php echo $row['status']; ?>">
                <input type="hidden" name="trigger" value="gobaby">
            </div>
        </div>
    </form>
</div>
<!-- including AceEditor -->
<script src="<?php echo base_url(); ?>include/plugins/ace-editor/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url(); ?>include/plugins/ace-editor/ext-static_highlight.js" type="text/javascript" charset="utf-8"></script>

<!-- Include TinyMCE. -->
<script type="text/javascript" src="<?php echo base_url(); ?>include/plugins/tinymce/tinymce.min.js"></script>
<script>

    function openModal(modal_id)
    {
        $('#'+modal_id).css({'display':'flex'});
    }
    function closeModal(modal_id)
    {
        $('#'+modal_id).css({'display':'none'});
    }

    var footerContents = $("#page_footer_contents");
    var headerContents = $("#page_header_contents");
    // Body Ace Editor Instance
    var aceEditorBody = ace.edit("body-contents-editor");
    aceEditorBody.setTheme("ace/theme/dracula");
    aceEditorBody.session.setMode("ace/mode/html");
    aceEditorBody.setValue($('#page_contents').val());
    aceEditorBody.on("blur",function()
    {
        tinyMCE.get('page_contents').setContent(aceEditorBody.getValue());
    });
    aceEditorBody.setOptions({
        fontSize: "11pt"
    });

    // Header Ace Editor Instance
    var aceEditorHeader = ace.edit("header-contents-editor");
    aceEditorHeader.setTheme("ace/theme/dracula");
    aceEditorHeader.session.setMode("ace/mode/html");
    aceEditorHeader.setValue(headerContents.val());
    aceEditorHeader.on("blur",function()
    {
        headerContents.val(aceEditorHeader.getValue());
    });
    aceEditorHeader.setOptions({
        fontSize: "11pt"
    });

    // Footer Ace Editor Instance
    var aceEditorFooter = ace.edit("footer-contents-editor");
    aceEditorFooter.setTheme("ace/theme/dracula");
    aceEditorFooter.session.setMode("ace/mode/html");
    aceEditorFooter.setValue(footerContents.val());
    aceEditorFooter.on("blur",function()
    {
        footerContents.val(aceEditorFooter.getValue());
    });
    aceEditorFooter.setOptions({
        fontSize: "11pt"
    });

    tinymce.init({
        selector: "#page_contents",
        plugins: [
            "alignment containers columns elements advlist hr autolink lists link image charmap anchor paste colorpicker imagetools textcolor",
            "searchreplace code fullscreen media"
        ],
        toolbar1: "undo redo | containers columns elements | formatselect styles bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat image media | code fullscreen",
        height : 500,
        menubar:false,
        relative_urls: true,
        content_css: ['<?php echo $content_css; ?>','<?php echo base_url() ;?>include/articlar-styles.css','<?php echo base_url() ;?>include/tiny-markers.css'],
        plugin_preview_width: screen.width * .95,
        plugin_preview_height: screen.height * .95,
        /* calling onblur for transfer data to Ace Editor */
        init_instance_callback: function (editor) {
            editor.on('blur', function (e) {
                aceEditorBody.setValue(tinyMCE.get('page_contents').getContent());
            });
        },
        /* calling custom filebrowser */
        file_browser_callback :
            function(field_name, url, type, win){
                var filebrowser = "<?php echo base_url().$mod.'/admin/filebrowser/'.$row['author-id']; ?>";
                tinymce.activeEditor.windowManager.open({
                    title : "Asset Browser",
                    width : $(window).width() * .97,
                    height: $(window).height(),
                    url : filebrowser
                }, {
                    window : win,
                    input  : field_name
                });
                return false;
            }
    });
    function cancel_upload()
    {
        $('#picBox').css('background-image',"url('<?php echo base_url().'uploadir/picture.svg'; ?>");
        $('#page_title_picture').val('');
    }

</script>