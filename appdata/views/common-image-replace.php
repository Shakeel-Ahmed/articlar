<?php if($mime == 'application/octet-stream') $mime = 'image/svg+xml'; else $mime = 'image/*';?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>include/articlar-admin-styles.css">
<script type="text/javascript" src="<?php echo base_url(); ?>include/javascripts.js"></script>
<div class="container">
    <div class="row space-top">
        <div class="col-12 column center cross-center">
            <div id="picBox" class="picture-box" onclick="triggerField('#image_field');" style="background-image: url(<?php echo base_url($this->config->item('uploadir').'authors/'.$author_id.'/'.$image); ?>); background-size: contain; height: 360px; width:640px;">

            </div>
            <p class="space-top text-center">
                This will replace the image in every article you have used it.<br/>
                You may need to clear browser cache to see changes.
            </p>
            <span>
                <button class="button" onclick="document.getElementById('form').submit();">Replace</button>
                <button class="button" onclick="go('<?php echo base_url().$mod.'/admin/filebrowser/'.$author_id; ?>');" style="cursor: pointer;">Cancel</button>
            </span>
            <form id="form" action="<?php echo base_url().$mod.'/admin/replace-image/'.$author_id.'/'.$image; ?>" method="post" enctype="multipart/form-data">
                <input id="image_field" type="file" name="image_field" accept="<?php echo $mime; ?>"  onchange="show(event,'picBox');" style="height:0px;width:0px;visibility:hidden;">
                <input name="trigger" value="goBabyReplaceImage" type="hidden">
            </form>
        </div>
    </div>
</div>