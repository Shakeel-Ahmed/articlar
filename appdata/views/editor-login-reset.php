<div class="container">
    <div class="row center">
        <div class="col-12">
            <div class="login-card">
            <h4 class="h4 cross-center">Reset Password <i class="art-icon space-left-1">cached</i></h4>
            <hr/>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="resetPassword">
                <div class="row">
                    <div class="col-12">
                        <label><i class="art-icon space-right-1">person</i> New Login</label>
                        <input type="text" class="form space-top-1" name="reset_login" id="reset_login" minlength="6" maxlength="128" required>
                    </div>
                    <div class="col-12 space-top">
                        <label><i class="art-icon space-right-1">https</i> New Password </label>
                        <input type="password" class="form space-top-1" name="reset_password" id="reset_password" minlength="6" maxlength="128" required>
                    </div>
                    <div class="col-12 cross-center end space-top">
                        Show Password <i class="art-icon" id="show-hide-password-toggle" style="cursor: pointer;" onclick="showHidePassword('reset_password');">check_box_outline_blank</i>
                    </div>
                    <div class="col-12 text-center">
                        <input type="submit" class="button space-top" value="Reset">
                        <input type="hidden" name="trigger" value="goBabyResetPassword">
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>