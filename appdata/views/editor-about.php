<div class="container">
    <div class="row">
        <div class="col-12 text-center space-top-1">
            <img src="<?php echo base_url(); ?>images/articlar.svg" alt="Articlar Logo" style="max-width:720px;width:100%;"><br/>
            <img src="<?php echo base_url(); ?>images/blogger-text.svg" alt="Blogger Logo" class="vspace-1" style="max-width:360px;width:100%;">
            <h2>PRO</h2>
            <p>LICENCED TO: <span class="text-color-highlight"><?php if($lickey) echo strtoupper($owner); else echo 'Not Licenced' ?></span></p>
            <p>EXPIRING: <span class="text-color-highlight"><?php if($lickey) echo dateFormat($expiry,'d M Y'); else echo 'Expired' ?></span></p>
            <p>LICENCE KEY: <span class="text-color-highlight"><?php if($lickey) echo $lickey; else echo 'Not Installed' ?></span></p>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                <p class="space-top">
                <div class="col-12">
                    <h5 style="text-align: center">Install Licence Key</h5>
                    <input type="text" name="licence-ley" class="form" style="max-width: 360px; text-align: center;" required minlength="16">
                </div>
                <button class="button space-top">Install</button>
                <input type="hidden" name="key-trigger" value="goBabyInstallKey">
                </p>
            </form>
            <hr/>
            <p>Installed version <span class="text-color-highlight"><?php echo $version; ?></span></p>
            <p>Latest version <span class="text-color-highlight"><?php echo $current ;?></span></p>
            <p>Release date <span class="text-color-highlight"><?php echo dateFormat($released,'d M Y') ;?></span></p>
            <?php if($current > $version) { ?>
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                    <p class="space-top">
                        <button class="button">Update</button>
                        <input type="hidden" name="trigger" value="goBabyUpdateArticlar">
                    </p>
                </form>
            <?php } ?>
            <div style="text-align: left;"><?php echo '<p>'.str_replace("\n","</p><p>",$description).'</p>'; ?></div>
            <p>CHANGE LOG:</p>
            <div style="text-align: left;"><?php echo '<p>'.str_replace("\n","</p><p>",$clog).'</p>'; ?></div>
        </div>
    </div>
</div>
<script>

    function update(location,file,element_id)
    {
        dialog.confirm({
            title: "Delete Asset",
            message: "Are you sure you want to delete this asset?",
            cancel: "No",
            button: "Yes",
            required: true,
            callback: function(value)
            {
                if(value===true)
                {
                    $.post(location,{"picture":file}, function(data, status){
                        if((data=='false' || data=='') && status=='success')
                        {
                            dialog.alert
                            ({
                                title: "Failed Process",
                                message: "Unable to delete the asset due to unexpected error. Please try again little later.",
                                button: "Ok",
                                animation: "fade"
                            })
                        }
                        else if(data=='true' && status=='success')
                        {
                            deleteFX('#'+element_id);
                        }
                    });
                }
            }
        });
    }

</script>