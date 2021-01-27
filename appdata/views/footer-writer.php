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
                <div onclick="go('<?php echo base_url(); ?>author/admin/');" class="cross-center"><i class="art-icon">dashboard</i><span class="space-left-1">Dashboard</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>author/admin/create-new-article');" class="cross-center"><i class="art-icon">library_add</i><span class="space-left-1">New Article</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>author/admin/articles/datasearch/date/latest/1');" class="cross-center"><i class="art-icon">pages</i><span class="space-left-1">My Articles</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>author/admin/articles/datasearch/update/latest/1');" class="cross-center"><i class="art-icon">update</i><span class="space-left-1">Last Updated</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>author/admin/articles/datasearch/views/standard/1');" class="cross-center"><i class="art-icon">visibility</i><span class="space-left-1">Views</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="logout('<?php echo base_url(); ?>author/admin/logout/');" class="cross-center"><i class="art-icon">public</i><span class="space-left-1">Site</span></div>
            </span>
        </li>
        <?php if(isset($_SESSION['editor-id']) and isset($_SESSION['password-editor'])) { ?>
            <li>
                <span class="hover">
                    <div onclick="go('<?php echo base_url(); ?>editor/admin/');" class="cross-center"><i class="art-icon">account_circle</i><span class="space-left-1">Switch</span></div>
                </span>
            </li>
        <?php } ?>
        <li>
            <span class="hover">
                <div onclick="go('<?php echo base_url(); ?>author/admin/edit-my-profile/<?php echo $_SESSION['author-id']; ?>');" class="cross-center"><i class="art-icon">person</i><span class="space-left-1">Edit Profile</span></div>
            </span>
        </li>
        <li>
            <span class="hover">
                <div onclick="logout('<?php echo base_url(); ?>author/admin/logout/');" class="cross-center"><i class="art-icon">exit_to_app</i><span class="space-left-1">Logout</span></div>
            </span>
        </li>
    </ul>
</nav>
<!-- footer end -->
</body>
</html>