<div class="container">
    <h4>Create Author Profile</h4>
    <form autocomplete="off" name="createAuthor" id="createAuthor" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4 space-top">
                <label>Full Name</label>
                <input name="author_profile_name" id="author_profile_name" type="text" class="form" placeholder="full Name" required>
            </div>
            <div class="col-md-4 space-top">
                <label>Email</label>
                <input name="author_profile_email" id="author_profile_email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form" placeholder="valid email address" required value="email@example.com">
            </div>
            <div class="col-md-4 space-top">
                <label>Password</label>
                <input name="author_profile_pass" id="author_profile_pass" type="password" class="form" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 space-top">
                <label>About Author</label>
                <textarea name="author_profile_about" class="form"></textarea>
            </div>
            <div class="col-md-4 space-top">
                <label>Picture</label>
                <div id="picBox" class="picture-box" onclick="triggerField('#author_profile_picture');" style="background-image: url(<?php echo base_url('images/person.svg'); ?>);">
                    <span class="icon-small sa-cancel" onclick="cancel_upload();"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 center space-top">
                <input type="submit" class="button hallow" value="create">
                <input type="file" name="author_profile_picture" id="author_profile_picture" accept="image/*" onchange="show(event,'picBox');" style="display: none;">
                <input type="hidden" name="trigger" value="gobaby">
            </div>
        </div>
    </form>
</div>
<script>
    function cancel_upload()
    {
        $('#picBox').css('background-image',"url('<?php echo base_url('images/person.svg'); ?>");
        $('#author_profile_picture').val('');
    }
</script>