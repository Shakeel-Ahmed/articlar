<?php

    session_start();

    $version = 1;
    $stage = 'database-connection-verification';

    if(isset($_POST['trigger']) and $_POST['trigger'] == 'goBabyVerifyConnection')
    {
        $connection = @mysqli_connect
        (
            $_POST['database-server'],
            $_POST['database-user'],
            $_POST['database-password'],
            $_POST['database-name']
        );
        if(!$connection)
        {
            $stage = 'message';
            $error =
                [
                    'title' => 'Verification Failed',
                    'message' => 'Unable to connect with database with provided database details. Kindly check your database details and try again.',
                ];
        }
        else
        {
            $stage = 'site-details';
            $_SESSION['database-server']    = $_POST['database-server'];
            $_SESSION['database-user']      = $_POST['database-user'];
            $_SESSION['database-password']  = $_POST['database-password'];
            $_SESSION['database-name']      = $_POST['database-name'];
        }
    }
    if(isset($_POST['trigger']) and $_POST['trigger'] == 'goBabyInstall')
    {

        $exe_path = '//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $baseURL = str_replace(basename($_SERVER['PHP_SELF']),'',$exe_path);

        $config_data = file_get_contents('config.install');
        $config_data = str_replace('[art-baseurl]',$baseURL,$config_data);
        $config_file_write_status = file_put_contents('appdata/config/config.php',$config_data);
        if(!$config_file_write_status)
        {
            $stage = 'message';
            $error =
                [
                    'title' => 'Config File Write Fail',
                    'message' => 'Unable to write config file. Please check installer.php file read/write permissions andf try again.',
                ];
            goto end;
        }

        $connection_values  = [$_SESSION['database-server'],$_SESSION['database-user'],$_SESSION['database-password'],$_SESSION['database-name']];
        $connection_tags    = ['[art-host]','[art-user]','[art-password]','[art-name]'];

        $database_config_data = file_get_contents('database.install');
        $database_config_data = str_replace($connection_tags,$connection_values,$database_config_data);

        $database_config_file_write_status = file_put_contents('appdata/config/database.php',$database_config_data);
        if(!$database_config_file_write_status)
        {
            $stage = 'message';
            $error =
                [
                    'title' => 'Database Connection File Write Fail',
                    'message' => 'Unable to write database coneectiong config file. Please check installer.php file read/write permissions and try again.',
                ];
            goto end;
        }
        $mysqli = new mysqli
            (
                $_SESSION['database-server'],
                $_SESSION['database-user'],
                $_SESSION['database-password'],
                $_SESSION['database-name']
            );
        if($mysqli->connect_errno)
        {
            $stage = 'message';
            $error =
                [
                    'title' => 'Database Connection Fail',
                    'message' => $mysqli->connect_error,
                ];
            goto end;
        }

        // setting up database
        // editor login setup
        $editor =
            [
                'editor-id' => 'admin',
                'login'     => md5(trim($_POST['editor-login'])),
                'hash'      => password_hash(trim($_POST['editor-password']), PASSWORD_BCRYPT)
            ];

        $db_data = file_get_contents('articlar.sql');
        $db_data = explode(';',$db_data);
        $db_data[] = "INSERT INTO `{$_SESSION['database-name']}`.`settings` (`name`,`owner`,`owner-email`,`email`,`address`,`product`,`version`,`lic-key`) VALUES ('{$_POST['blog-name']}', '{$_POST['blog-owner']}','{$_POST['blog-owner-email']}','{$_POST['blog-email']}', '{$_POST['blog-address']}','bloggerpro',$version,'0000000000000000')";
        $db_data[] = "INSERT INTO `{$_SESSION['database-name']}`.`login-editor` (`editor-id`, `login`, `hash`) VALUES ('admin','{$editor['login']}','{$editor['hash']}')";

        foreach($db_data as $query)
        {
            if($query) $mysqli->multi_query($query);
        }

        $unlinks = ['index.php','config.install','database.install','articlar.sql'];
        foreach($unlinks as $unlink) unlink($unlink);
        unset($_SESSION);

        rename('index.php.original','index.php');
        $stage = 'congratulations';
    }

    end:
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="include/articlar-admin-styles.css">
    <script type="text/javascript" src="include/javascripts.js"></script>
    <title>Install Articlar Blogger</title>
</head>
<body>
<div class="sticky-footer">
    <div class="header">
        <img class="blogger-logo" src="images/blogger-mono.svg" style="max-width:50px;" alt="Blogger Logo">
        <img src="images/articlar.svg" style="max-width:200px; opacity: .8;" alt="Articlar Logo">
        <div class="d-none d-md-flex text-color-highlight cross-center" style="margin-left: auto;">
            <i class="art-icon" style="font-size: 32px;">cloud_download</i><sapn class="space-left-1" style="font-size:24px;letter-spacing:.2em;">INSTALLER</sapn>
        </div>
    </div>
    <div class="container">
        <?php if($stage == 'database-connection-verification') { ?>
        <h4>Provide <span class="text-color-highlight">Database</span> Details</h4>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
        <div class="row cross-center space-top">
            <div class="col-12">
                <label for="database-name">Database Host Server</label>
                <input name="database-server" id="database-server" type="text" class="form" placeholder="localhost" required>
            </div>
            <div class="col-12 space-top-1">
                <label for="database-name">Database Name</label>
                <input name="database-name" id="database-name" type="text" class="form" placeholder="articlar_database" required>
            </div>
            <div class="col-12 space-top-1">
                <label for="database-user">Database User</label>
                <input name="database-user" id="database-user" type="text" class="form" placeholder="root" required>
            </div>
            <div class="col-12 space-top-1">
                <label for="database-user">Database User Password</label>
                <input name="database-password" id="database-password" type="text" class="form" placeholder="1234">
            </div>
            <div class="col-md-12 center space-top">
                <input type="submit" class="button hallow" value="verify">
                <input type="hidden" name="trigger" value="goBabyVerifyConnection">
            </div>
        </div>
        </form>
        <?php } ?>
        <?php if($stage == 'site-details') { ?>
        <h4 class="space-top">Provide <span class="text-color-highlight">Blog Site</span> Details</h4>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
        <div class="row cross-center space-top">
            <div class="col-12 space-top-1">
                <label>Blog Name</label>
                <input name="blog-name" type="text" class="form" placeholder="My Blog" required>
            </div>
            <div class="col-12 space-top-1">
                <label>Blog Owner</label>
                <input name="blog-owner" type="text" class="form" placeholder="Individual, bussiness, organization etc." required>
            </div>
            <div class="col-12 space-top-1">
                <label>Owner Contact Email</label>
                <input name="blog-owner-email" type="email" class="form" placeholder="Individual, bussiness, organization etc." required>
            </div>
            <div class="col-12 space-top-1">
                <label>Blog Email</label>
                <input name="blog-email" type="email" class="form" placeholder="contact@<?php echo $_SERVER['HTTP_HOST']; ?>" required>
            </div>
            <div class="col-12 space-top-1">
                <label>Blog Address</label>
                <input name="blog-address" type="text" class="form" placeholder="Physical Address">
            </div>
            <div class="col-12 space-top-1">
                <label>Editor Login</label>
                <input name="editor-login" type="text" class="form" placeholder="login" minlength="6" required>
            </div>
            <div class="col-12 space-top-1">
                <label>Editor Password</label>
                <input name="editor-password" type="password" class="form" placeholder="123456" minlength="8" required>
            </div>
            <div class="col-md-12 center space-top">
                <input type="submit" class="button hallow" value="Install">
                <input type="hidden" name="trigger" value="goBabyInstall">
            </div>
        </div>
        </form>
        <?php } ?>
        <?php if($stage == 'congratulations') { ?>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4>Congratulations</h4>
                    <hr/>
                    <p class="text-center">Articlar Blogger setup has been successfully installed. You can start using your Articlar Blogger content management system by loging into your Editor account.</p>
                    <p class="text-center">
                        <input onclick="go('<?php echo $baseURL.'editor/admin/enter'; ?>');" type="submit" class="button" value="Login">
                        <input onclick="go('<?php echo $baseURL; ?>');" type="submit" class="button" value="Site">
                    </p>
                </div>
            </div>
        <?php } ?>
        <?php if($stage == 'message') { ?>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4><?php echo $error['title']; ?></h4>
                    <hr/>
                    <p class="text-center"><?php echo $error['message']; ?></p>
                    <p class="text-center"><input onclick="goBack();" type="submit" class="button" value="back"></p>
                </div>
            </div>
        <?php } ?>
    </div>
    <!-- contents end -->
</div>
<div class="footer">
    <div class="text-center">
        <img src="images/articlar.svg" style="max-width: 300px; opacity: .8;">
    </div>
</div>
</body>
</html>