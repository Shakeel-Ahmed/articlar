<div class="container">
    <div class="row center">
        <div class="col-12 card" style="max-width: 50%;">
            <h4 class="h4 vspace cross-center">Recover Password <i class="art-icon space-left-1">lock_open</i></h4>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="recoverPassword">
                <div class="row">
                    <div class="col-12">
                        <label><i class="art-icon space-right-1">email</i> Your Registered Email</label>
                        <input type="email" pattern="" class="form" name="recover_email" id="recover_email" required>
                    </div>
                    <div class="col-12 text-center">
                        <input type="submit" class="button space-top" value="Recover">
                        <input type="hidden" name="trigger" value="goBabyRecoverPassword">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>