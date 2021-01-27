<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>include/articlar-admin-styles.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>include/javascripts.js"></script>
    <script>
        $(document).ready(function() {
            $("#menu").mmenu({

                navbar: {
                    title: '<img src="<?php echo base_url(); ?>images/articlar.svg" style="width:180px; opacity:.85;">'
                },
                "extensions": [
                    /*"fx-menu-slide",*/
                    /*"fx-panels-slide",*/
                    /*"fx-listitems-slide",*/
                    "position-right"
                ],
            }).css("visibility","visible");
            var menuAPI = $("#menu").data( "mmenu" );
            $("#menu-close").click(function() {
                menuAPI.close();
            });
        });
    </script>
    <title><?php echo $title; ?></title>
</head>
<body>
<!-- sticky footer -->
<div class="sticky-footer">
    <div class="header">
        <img class="blogger-logo" onclick="go('<?php echo base_url(); ?>author/admin/');" src="<?php echo base_url(); ?>images/blogger-mono.svg" style="max-width:50px;" alt="Blogger Logo">
        <img src="<?php echo base_url(); ?>images/articlar.svg" style="max-width:200px; opacity: .8;" alt="Articlar Logo">
        <div class="cross-center" style="margin-left: auto;">
            <div class="menu-icon d-none d-md-block hover">
                <div class="art-icon" onclick="go('<?php echo base_url(); ?>author/admin');">dashboard</div><div>Dash</div>
            </div>
            <div class="menu-icon d-none d-md-block hover" onclick="go('<?php echo base_url(); ?>author/admin/articles/datasearch/date/standard/1')">
                <div class="art-icon">pages</div><div>Arts</div></a>
            </div>
            <div class="menu-icon d-none d-md-block hover" onclick="go('<?php echo base_url(); ?>author/admin/create-new-article');">
                <div class="art-icon">library_add</div><div>New</div></a>
            </div>
            <?php if((isset($_SESSION['editor-id']) and isset($_SESSION['password-editor'])) or (isset($_COOKIE['editor-id']) and isset($_COOKIE['password-editor']))) { ?>
                <div class="menu-icon d-none d-md-block hover" onclick="go('<?php echo base_url(); ?>editor/admin/')">
                    <div class="art-icon">account_circle</div><div>Switch</div></a>
                </div>
            <?php } ?>
            <div class="menu-icon">
                <a href="#menu"><i class="art-icon hover">menu</i><div>menu</div></a>
            </div>
        </div>
    </div>