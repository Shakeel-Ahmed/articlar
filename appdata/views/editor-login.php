<div class="container">
    <div class="row center">
        <div class="col-12">
            <div class="login-card">
                <h4 class="h4 cross-center"><i class="art-icon space-right-1">account_circle</i> Editor Login</h4>
                <hr/>
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="editorLogin">
                    <div class="row">
                        <div class="col-12">
                            <label><i class="art-icon space-right-1">perm_identity</i>Login ID</label>
                            <input type="text" class="form" name="editor_name" id="editor_name">
                        </div>
                        <div class="col-12">
                            <label class="space-top"><i class="art-icon space-right-1">lock</i>Password</label>
                            <input type="password" class="form" name="editor_secret" id="editor_secret" style="min-width: 280px;">
                        </div>
                        <div class="col-12 cross-center space-top-1">
                            <a class="hover" href="<?php echo base_url(); ?>editor/admin/recover-password/">I Forgot My Password?</a>
                            <span class="cross-center" style="margin-left: auto;">Remember <i class="art-icon" id="show-hide-rememeber-toggle" style="cursor: pointer;" onclick="checkBox('remember',this.id);">check_box_outline_blank</i></span>
                            <span class="cross-center" style="margin-left: 15px;">Show<i class="art-icon" id="show-hide-password-toggle" style="cursor: pointer;" onclick="showHidePassword('editor_secret');">check_box_outline_blank</i></span>
                        </div>
                        <div class="col-12 text-center">
                            <input type="submit" class="button space-top" value="login">
                            <input type="hidden" name="remember" id="remember" value="false">
                            <input type="hidden" name="trigger" value="gobaby">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>