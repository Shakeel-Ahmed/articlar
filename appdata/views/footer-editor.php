</div>
<div class="footer">
    <div class="text-center">
        <img src="<?php echo base_url(); ?>images/articlar.svg" style="max-width: 300px; opacity: .8;">
    </div>
</div>
<style>
    .mm-navbar {
        --mm-color-background: #152533;
        --mm-color-text-dimmed: #fff;
        --mm-navbar-size: 70px;
        --mm-color-button: #fff;

    }
    .mm-panel {
        --mm-color-background: #1a2226;
        --mm-color-text: #cccccc;
        --mm-color-button: #4bb5ef;
    }
    .hover {cursor: pointer}
</style>
<nav id="menu" style="visibility: hidden;">
    <ul style="margin-top: 10px;">
        <li>
            <span id="menu-close" class="hover">
                <div class="cross-center"><i class="art-icon">cancel</i><span class="space-left-1">Close</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>editor/admin/');" class="cross-center"><i class="art-icon">dashboard</i><span class="space-left-1">Dashboard</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>editor/admin/articles/datasearch/date/latest/1');" class="cross-center"><i class="art-icon">pages</i><span class="space-left-1">Articles</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>editor/admin/authors/datasearch/updated/standard/1');" class="cross-center"><i class="art-icon">supervisor_account</i><span class="space-left-1">Authors</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>editor/admin/create-author-profile');" class="cross-center"><i class="art-icon">person_add</i><span class="space-left-1">New Author</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>editor/admin/installed-themes/datasearch/date/latest/1');" class="cross-center"><i class="art-icon">insert_photo</i><span class="space-left-1">Themes</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>editor/admin/install-theme');" class="cross-center"><i class="art-icon">add_photo_alternate</i><span class="space-left-1">Install New Theme</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>editor/admin/template-validator');" class="cross-center"><i class="art-icon">assignment_turned_in</i><span class="space-left-1">Theme Validator</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>editor/admin/about');" class="cross-center"><i class="art-icon">new_releases</i><span class="space-left-1">About</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>editor/admin/settings');" class="cross-center"><i class="art-icon">settings</i><span class="space-left-1">Settings</span></div>
            </span>
        </li>
        <?php if(isset($_SESSION['author-id']) and isset($_SESSION['password-author'])) { ?>
            <li>
                <span class="hover">
                    <div onclick="go('<?php echo base_url(); ?>author/admin/');" class="cross-center"><i class="art-icon">account_circle</i><span class="space-left-1">Switch</span></div>
                </span>
            </li>
        <?php } ?>
        <li>
            <span class="hover">
                <div onclick="logout('<?php echo base_url(); ?>editor/admin/logout/');" class="cross-center"><i class="art-icon">exit_to_app</i><span class="space-left-1">Logout</span></div>
            </span>
        </li>
    </ul>
</nav>
<!-- footer end -->
</body>
</html>