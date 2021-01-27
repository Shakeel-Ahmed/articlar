<div class="container">
    <h4 class="mt-4 mb-4 mt-md-0 mb-md-0 cross-center"> Settings <i class="art-icon text-color-safe hover" onclick="go('<?php echo base_url('editor/admin/'); ?>');" style="margin-left: auto;">cancel</i></h4>
    <form autocomplete="off" name="settings" id="settings" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12 space-top">
            <label>Site Name *</label>
            <input name="site-name" id="site-name" type="text" class="form" placeholder="Blog, Business, News or Mag name" value="<?php echo $row['name']; ?>" required>
        </div>
        <div class="col-md-12 space-top">
            <label>Site Owner *</label>
            <input name="site-owner" id="site-owner" type="text" class="form" placeholder="Individual, Company or Business " value="<?php echo $row['owner']; ?>" required>
        </div>
        <div class="col-md-12 space-top">
            <label>Owner Email*</label>
            <input name="site-owner-email" id="site-owner-email" type="text" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form" placeholder="myname@exmaple.com" value="<?php echo $row['owner-email']; ?>" required>
        </div>
        <div class="col-md-12 space-top">
            <label>Admin Email *</label>
            <input name="site-email" id="site-email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form" placeholder="admin@myblog.com" value="<?php echo $row['email']; ?>" required>
        </div>
        <div class="col-md-12 space-top">
            <label>Address / Tagline</label>
            <input name="site-address" id="site-address" type="text" class="form" placeholder="Business Addresss" value="<?php echo $row['address']; ?>">
        </div>
        <div class="col-md-6 space-top">
            <label>Site Logo</label>
            <div id="picBoxLogo" class="picture-box" onclick="triggerField('#logo');" style="background-image:url('<?php echo $row['site-logo']; ?>'); background-size: contain;">
                <span class="icon-small sa-cancel" onclick="cancel_upload();"></span>
            </div>
        </div>
        <div class="col-md-6 space-top">
            <label>Placeholder Article Image</label>
            <div id="picBoxImage" class="picture-box" onclick="triggerField('#missing_image');" style="background-image:url('<?php echo $row['default-image']; ?>'); background-size: contain;">
                <span class="icon-small sa-cancel" onclick="cancel_upload();"></span>
            </div>
        </div>
    </div>
    <div class="row space-top">
        <div class="col-md-6 text-center text-md-right">
            <input type="submit" class="button" value="cancel" onclick="cancelSave();return false;">
        </div>
        <div class="col-md-6 text-center text-md-left">
            <input type="submit" class="button" value="save">
            <input type="file" name="logo" id="logo" accept="image/*" onchange="show(event,'picBoxLogo');" style="display: none;">
            <input type="file" name="missing_image" id="missing_image" accept="image/*" onchange="show(event,'picBoxImage');" style="display: none;">
            <input type="hidden" name="trigger" value="goBabySetThingsUp">
        </div>
    </div>
    </form>
</div>