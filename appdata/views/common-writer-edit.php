<div class="container">
    <h4 class="mt-4 mt-md-0">Edit <span class="text-color-highlight"><?php echo $row['name']; ?></span> Profile</h4>
    <form autocomplete="off" name="createAuthor" id="createAuthor" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4 space-top">
                <label><i class="art-icon">person</i> Full Name</label>
                <input name="author_profile_name" id="author_profile_name" type="text" class="form" placeholder="full Name" value="<?php echo $row['name']; ?>" required>
            </div>
            <div class="col-md-4 space-top">
                <label><i class="art-icon">email</i>Email</label>
                <input name="author_profile_email" id="author_profile_email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form" placeholder="valid email address" value="<?php echo $row['email']; ?>" required>
            </div>
            <div class="col-md-4 space-top">
                <label><i class="art-icon">vpn_key</i>Password</label>
                <input name="author_profile_pass" id="author_profile_pass" type="password" class="form" placeholder="My Secret Password">
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 space-top">
                <label><i class="art-icon">assignment_ind</i> About Author</label>
                <textarea name="author_profile_about" id="author_profile_about" class="form"><?php echo $row['about']; ?></textarea>
            </div>
            <div class="col-md-4 space-top">
                <label>Picture</label>
                <div id="picBox" class="picture-box hover" onclick="triggerField('#author_profile_picture');" style="background-image:url('<?php echo $row['picture'];?>'); text-align: right; font-size: 36px;">
                    <!--<i class="art-icon text-color-publish mt-3 mr-3" onclick="cancel_upload();">cancel</i>-->
                </div>
            </div>
        </div>
        <div class="row space-top">
            <div class="col-md-6 text-center text-md-right">
                <input type="submit" class="button" value="cancel" onclick="cancelSave();return false;">
            </div>
            <div class="col-md-6 text-center text-md-left">
                <input type="submit" class="button" value="save">
                <input type="file" name="author_profile_picture" id="author_profile_picture" accept="image/*" onchange="show(event,'picBox');" style="display: none;">
                <input type="hidden" name="trigger" value="gobaby">
            </div>
        </div>
    </form>
</div>
<!--<script>
    function cancel_upload()
    {
        $('#picBox').css('background-image',"url('</?php echo base_url('images/nopicture.svg'); ?>");
        $('#author_profile_picture').val('');
    }
</script>-->