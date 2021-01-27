<?php if($mime == 'application/octet-stream') $mime = 'image/svg+xml';?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('include/combined.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('include/combined.js'); ?>"></script>
<div class="section">
    <div class="row">
        <div class="col-12 column center cross-center">
            <div id="picBox" class="image-field" onclick="triggerField('#image_field');" style="background-image: url(<?php echo base_url($this->config->item('uploadir').'authors/admin/'.$image); ?>); height: 360px; width:360px;">

            </div>
            <p style="text-align: center;">This will replace this image in every article you have used it.</p>
            <span>
                <button class="button background-warning" onclick="document.getElementById('form').submit();">Replace</button>
                <button class="button" onclick="window.location.href='<?php echo base_url('editor/admin/filebrowser'); ?>';" style="cursor: pointer;">BACK</button>
            </span>
            <form id="form" action="<?php echo base_url('editor/admin/replace-image/'.$image); ?>" method="post" enctype="multipart/form-data">
                <input id="image_field" type="file" name="image_field" accept="<?php echo $mime; ?>"  onchange="show(event,'picBox');" style="height:0px;width:0px;visibility:hidden;">
                <input name="trigger" value="goBabyReplaceImage" type="hidden">
            </form>
        </div>
    </div>
</div>