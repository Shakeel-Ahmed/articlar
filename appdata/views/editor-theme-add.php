<div class="container">
    <h4 class="text-center text-md-left mt-4 mb-4 mt-md-0 mb-md-0">Theme Upload</h4>
    <form name="addTheme" id="addTheme" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <div class="row space-top">
        <div class="col-md-12">
            <label>Upload Theme .zip File</label>
            <div id="displayUploadFileName" onclick="triggerField('#uploadField');" class="form" style="text-align: center; cursor: pointer">click to upload theme</div>
        </div>
    </div>
    <div class="row space-top">
        <div class="col-md-12 center">
            <input type="submit" class="button hallow" value="upload" id="themeAdd" disabled="disabled" style="opacity:.2; animation-duration: 0s; cursor: auto;">
            <input type="hidden" name="trigger" value="gobaby">
            <input type="file" onchange="uploadName(this);" name="theme_file" id="uploadField" accept="application/zip" style="display:none;">
        </div>
    </div>
    </form>
</div>
<script>
    function uploadName(field)
    {
        $('#displayUploadFileName').html(field.value);
        $('#themeAdd').prop('disabled', false).css({"opacity":1, "animation-duration" : "1s","cursor":"pointer"});
    }
</script>
