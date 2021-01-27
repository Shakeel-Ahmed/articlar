<div class="container">
    <form name="validate_template" id="update_template" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <div class="row space-top">
        <div class="col-md-12">
            <h5 class="vspace">Base Template Code</h5>
            <textarea name="template-main" id="template-main" class="form">Cut and paste your main template code here</textarea>
        </div>
    </div>
    <div class="row space-top">
        <div class="col-md-12">
            <h5 class="vspace">Blog Listing Template Code</h5>
            <textarea name="template-blog" id="template-blog" class="form">Cut and paste your blog lisiting template code here</textarea>
            <div class="space-top" style="text-align: center;">
                <button class="button hallow">Check</button>
                <input type="hidden" name="trigger" value="goBaby">
            </div>
        </div>
    </div>
    </form>
</div>
<script type="text/javascript" src="/include/plugins/autosize/autosize.min.js"></script>
<script>
    $(document).ready(function(){
        autosize($('#template-main'));
        autosize($('#template-blog'));
    });
</script>
