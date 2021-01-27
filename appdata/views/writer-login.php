<div class="container">
    <div class="row center">
        <div class="col-12">
            <div class="login-card">
                <h4 class="cross-center"><i class="art-icon space-right-1">account_circle</i> Author Login</h4>
                <hr/>
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="authorLogin">
                    <div class="row">
                        <div class="col-12">
                            <label><i class="art-icon space-right-1">email</i>Email</label>
                            <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form" name="author_name" id="author_name">
                        </div>
                        <div class="col-12 space-top">
                            <label><i class="art-icon space-right-1">lock</i> Password</label>
                            <input type="password" class="form" name="author_secret" id="author_secret" style="min-width: 280px;">
                        </div>
                        <div class="col-12 cross-center mt-3">
                            <a class="hover" href="<?php echo base_url(); ?>author/admin/recover-password/">Forgot Password?</a>
                            <span class="cross-center" style="margin-left: auto;">Remember <i class="art-icon" id="show-hide-rememeber-toggle" style="cursor: pointer;" onclick="checkBox('remember',this.id);">check_box_outline_blank</i></span>
                            <span class="cross-center" style="margin-left: 15px;">Show <i class="art-icon" id="show-hide-password-toggle" style="cursor: pointer;" onclick="showHidePassword('author_secret');">check_box_outline_blank</i></span>
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