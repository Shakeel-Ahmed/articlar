<span style="position:fixed; right: 40px;top: 100px; z-index: 2;">
    <div class="menu-icon">
        <i class="art-icon hover" onclick="updateThemeTemplate('<?php echo base_url('editor/admin/update-theme-template/'.$row['theme-id'].'/ajax'); ?>');">save</i>
        <div>SAVE</div>
    </div>
</span>
<form name="update_template" id="update_template" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
<div class="container-fluid">
    <div class="row space-top">
        <div class="col-md-12">
            <h5 class="vspace text-center text-md-left">Base Template Code</h5>
            <div id="template-editor" class="ace-editor" style="height: 75vh;"><?php echo $row['template']; ?></div>
            <textarea rows="1" name="template" id="template" style="visibility: hidden; position: fixed; height: 1px !important;"><?php echo $row['template']; ?></textarea>
        </div>
    </div>
    <div class="row space-top">
        <div class="col-md-12">
            <h5 class="vspace text-center text-md-left">Blog Listing Template Code</h5>
            <div id="template-blog-editor" class="ace-editor" style="height: 75vh;"><?php echo $row['template-blog']; ?></div>
            <div class="space-top" style="text-align: center;">
                <button class="button hallow">Save & Exit</button>
                <input type="hidden" name="trigger" value="goBaby">
            </div>
            <textarea rows="1" name="template-blog" id="template-blog" style="visibility: hidden; position: fixed; height: 1px !important;"><?php echo $row['template-blog']; ?></textarea>
        </div>
    </div>
</div>
</form>
<script src="<?php echo base_url(); ?>include/plugins/autosize/autosize.min.js" type="text/javascript"></script>
<!-- including AceEditor -->
<script src="<?php echo base_url(); ?>include/plugins/ace-editor/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url(); ?>include/plugins/ace-editor/ext-static_highlight.js" type="text/javascript" charset="utf-8"></script>
<script>

    var themeTemplate = $('#template');
    var aceEditorBody = ace.edit("template-editor");
    aceEditorBody.setTheme("ace/theme/dracula");
    aceEditorBody.session.setMode("ace/mode/html");
    aceEditorBody.setValue(themeTemplate.val());
    aceEditorBody.on("blur",function()
    {
        themeTemplate.val(aceEditorBody.getValue());//aceEditorBody.getValue());
    });
    aceEditorBody.setOptions({
        "fontSize": "11pt",
        "firstLineNumber": 0
    });

    // Blog lisiting ace editor code
    var themeBlogTemplate = $('#template-blog');
    var aceBlogEditorBody = ace.edit("template-blog-editor");
    aceBlogEditorBody.setTheme("ace/theme/dracula");
    aceBlogEditorBody.session.setMode("ace/mode/html");
    aceBlogEditorBody.setValue(themeBlogTemplate.val());
    aceBlogEditorBody.on("blur",function()
    {
        themeBlogTemplate.val(aceBlogEditorBody.getValue());//aceEditorBody.getValue());
    });
    aceBlogEditorBody.setOptions({
        "fontSize": "11pt",
        "firstLineNumber": 0
    });


</script>