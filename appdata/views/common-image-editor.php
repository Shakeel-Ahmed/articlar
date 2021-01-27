<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>include/articlar-admin-styles.css">
<script type="text/javascript" src="<?php echo base_url(); ?>include/javascripts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>include/plugins/camen/caman.mod.js"></script>

<?php
/*    if($admin === true)
    {
        $_SESSION['author-id'] = 'admin';
        $account = 'editor';
    }
    else $account = 'author';*/

    if($mime == 'application/octet-stream')
    {
        die
        (
            '<h5 class="space-top text-center">
               Can not edit SVG and Vector file formats
            </h5>
            <p style="text-align:center;">
                <button class="button" onclick="goBack();">BACK</button>
            </p>'
        );
    }
$display_image = base_url().$this->config->item('uploadir').'authors/'.$author_id.'/'.$image;
?>
<div class="container-fluid">
    <div class="row space-top">
        <div class="col-12 end">
            <i class="art-icon hover" onclick="go('<?php echo base_url().$mod.'/admin/filebrowser/'.$author_id ;?>');">cancel</i>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <img id="sourceImage" alt="source" class="borders" src="<?php echo $display_image ?>" style="max-width: 100%;">
        </div>
        <div class="col-md-2">
            <div id="Filters">
                <div class="Filter cross-center">
                    <i class="art-icon">brightness_7</i>
                    <div class="FilterSetting">
                        <input  id="br"
                                type="range"
                                min="-100"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="brightness">
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">brightness_1</i>
                    <div class="FilterSetting">
                        <input
                                type="range"
                                min="-100"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="contrast">
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">exposure</i>
                    <div class="FilterSetting">
                        <input
                                type="range"
                                min="-100"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="exposure">
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">tonality</i>
                    <div class="FilterSetting">
                        <input
                                type="range"
                                min="-100"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="saturation">
                        <!--<span class="FilterValue black-font">0</span>-->
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">filter_vintage</i>
                    <div class="FilterSetting">
                        <input
                                type="range"
                                min="-100"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="vibrance">
                        <!--<span class="FilterValue black-font">0</span>-->
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">palette</i>
                    <div class="FilterSetting">
                        <input
                                type="range"
                                min="0"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="hue">
                        <!--<span class="FilterValue black-font">0</span>-->
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">photo_filter</i>
                    <div class="FilterSetting">
                        <input
                                type="range"
                                min="0"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="sepia">
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">texture</i>
                    <div class="FilterSetting">
                        <input  type="range"
                                min="0"
                                max="10"
                                step="0.1"
                                value="0"
                                data-filter="gamma">
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">grain</i>
                    <div class="FilterSetting">
                        <input  type="range"
                                min="0"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="noise">
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">flip</i>
                    <div class="FilterSetting">
                        <input
                                type="range"
                                min="0"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="clip">
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">gradient</i>
                    <div class="FilterSetting">
                        <input
                                type="range"
                                min="0"
                                max="100"
                                step="1"
                                value="0"
                                data-filter="sharpen">
                    </div>
                </div>
                <div class="Filter cross-center">
                    <i class="art-icon">blur_on</i>
                    <div class="FilterSetting">
                        <input
                                type="range"
                                min="0"
                                max="20"
                                step="1"
                                value="0"
                                data-filter="stackBlur">
                    </div>
                </div>
            </div>
        </div>
        <div id="PresetFilters" class="col-md-4">
            <div class="row">
            <div class="col-md-4">
                <a data-preset="vintage"><button class="button hallow">Vintage</button></a>
                <a data-preset="lomo"><button class="button hallow">Lomo</button></a>
                <a data-preset="clarity"><button class="button hallow">Clarity</button></a>
                <a data-preset="sinCity"><button class="button hallow">Sin City</button></a>
                <a data-preset="sunrise"><button class="button hallow">Sunrise</button></a>
                <a data-preset="crossProcess"><button class="button hallow">Process</button></a>
            </div>
            <div class="col-md-4">
                <a data-preset="orangePeel"><button class="button hallow">Orange</button></a>
                <a data-preset="love"><button class="button hallow">Love</button></a>
                <a data-preset="grungy"><button class="button hallow">Grungy</button></a>
                <a data-preset="jarques"><button class="button hallow">Jarques</button></a>
                <a data-preset="pinhole"><button class="button hallow">Pinhole</button></a>
                <a data-preset="oldBoot"><button class="button hallow">Old Boot</button></a>
            </div>
            <div class="col-md-4">
                <a data-preset="glowingSun"><button class="button hallow">Sun Glow</button></a>
                <a data-preset="hazyDays"><button class="button hallow">Hazy Days</button></a>
                <a data-preset="herMajesty"><button class="button hallow">Majesty</button></a>
                <a data-preset="nostalgia"><button class="button hallow">Nostalgia</button></a>
                <a data-preset="hemingway"><button class="button hallow">Hemingway</button></a>
                <a data-preset="concentrate"><button class="button hallow">Concentrate</button></a>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
            <div class="text-center">
                <button class="button background-warning" onclick="imgcan();">Save</button>
                <button class="button" onclick="go('/<?php echo $mod.'/admin/filebrowser/'.$author_id ;?>');">Cancel</button>
                <form name="imageData" id="imageData" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data" style="text-align: center">
                    <input type="hidden" name="image_carrier" id="image_carrier" class="form">
                    <input type="hidden" name="image_name" value="<?php /* echo $image; */?>">
                    <input type="hidden" name="trigger" value="goBabySaveImage">
                </form>
            </div>
</div>
</div>
<script>
    function imgcan()
    {
        var canvas = document.getElementsByTagName('canvas')[0];
        document.getElementById('image_carrier').value = canvas.toDataURL('image/jpeg',.5);
        document.getElementById('imageData').submit();
    }
</script>