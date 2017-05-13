<?php
function __autoload($class_name) {
   require_once('cls/class.' . strtolower($class_name) . '.php');
}
require_once('inc/functions.inc.php');
$session = new SessionManager();
$users = new Users();

if($users->isLoggedIn()){
    transfers_to('./index.php');   
} 
require('inc/config.inc.php');

if(isset($_POST['login'])){
    $username = $_POST['username'] ? $_POST['username'] : '';
    $password = $_POST['password'] ? $_POST['password'] : '';
    if ($users->authenticate($username, $password)) {
        $users->push_logs_in();
        transfers_to("./index.php");
    } else {
        $alert = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Phần mềm quản lý Lãnh sự , Sở Ngoại vụ.">
    <meta name="keywords" content="Phần mêm, Lãnh sự, Sở NGoại vụ An Giang">
    <meta name="author" content="Trung tâm Tin học Trường Đai học An Giang, 18 Ung Văn Khiêm, Tp Long Xuyên, An Giang">
    <link rel='shortcut icon' type='image/x-icon' href="images/favicon.ico" />
    <title>Phần mềm Quản lý Lãnh sự - Sở Ngoại vụ An Giang</title>
    <link href="css/metro.css" rel="stylesheet">
    <link href="css/metro-icons.css" rel="stylesheet">
    <link href="css/metro-responsive.css" rel="stylesheet">
    <link href="css/metro-schemes.css" rel="stylesheet">
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/metro.js"></script>
     <style>
        .login-form {
            width: 25rem;
            height: 18.75rem;
            position: fixed;
            top: 50%;
            margin-top: -9.375rem;
            left: 50%;
            margin-left: -12.5rem;
            background-color: #ffffff;
            opacity: 0;
            -webkit-transform: scale(.8);
            transform: scale(.8);
        }
    </style>

    <script>
        $(function(){
            var form = $(".login-form");
            form.css({
                opacity: 1,
                "-webkit-transform": "scale(1)",
                "transform": "scale(1)",
                "-webkit-transition": ".5s",
                "transition": ".5s"
            });
            <?php if(isset($alert) && $alert == true) : ?>
                $.Notify({type: 'alert', caption: 'Thất bại', content: "Vui lòng điền đúng tài khoản và mật khẩu"});
            <?php endif; ?>
        });
    </script>
</head>
<body style="background-color: #067FD9;">
    <div class="login-form padding20 block-shadow">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="loginform">
            <h1 class="text-light">Đăng nhập hệ thống</h1>
            <hr class="thin"/>
            <br />
            <div class="input-control text full-size" data-role="input">
                <input type="text" name="username" id="username" required placeholder="Nhập tài khoản" />
                <button class="button helper-button clear"><span class="mif-cross"></span></button>
            </div>
            <br />
            <br />
            <div class="input-control password full-size" data-role="input">
                <input type="password" name="password" id="password" required placeholder="Nhập mật khẩu" />
                <button class="button helper-button reveal"><span class="mif-looks"></span></button>
            </div>
            <br />
            <br />
            <div class="form-actions">
                <button type="submit" name="login" id="login" class="button primary"><span class="mif-checkmark"></span> Đăng nhập</button>
            </div>
        </form>
    </div>

</body>
</html>