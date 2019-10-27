<?php 
session_start();
// エスケープ処理
require_once('../security/escape.php');
// パスワードのハッシュ化
require_once('../vendor/ircmaxell/password-compat/lib/password.php');
// クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
// データベースに接続
require_once('../dbconnect.php');

if ($_COOKIE['loginId'] !== '') {
    $loginId = $_COOKIE['loginId'];
}

if (!empty($_POST)) {
    $loginId = $_POST['loginId'];
    if($_POST['loginId'] !== '' && $_POST['password'] !== '') {
        $login = $db->prepare('SELECT * FROM member WHERE log=? AND pass=?');
        $login->execute(array(
            $_POST['loginId'],
            $_POST['password']
        ));
        $member = $login->fetch();

        if ($member) {
            $_SESSION['login-index'] = $member;
            $_SESSION['time'] = time();

            if ($_POST['save'] === 'on') {
                setcookie('loginId', $_POST['loginId'], time()+60*60*24*14);
            }
            setcookie('userId', $_SESSION['login-index'][0], time()+60*60*24*14, '/');        
            setcookie('username', $_SESSION['login-index'][1], time()+60*60*24*14, '/');    
            header('Location: ../main/main-index.php');
            exit();
        } else {
            $error['login'] = 'failed';
        }
    } elseif ($_POST['loginId'] == '') {
        $error['loginId'] = 'blank';
    } elseif ($_POST['password'] == '') {
        $error['password'] = 'blank';
    } 
}
?>

<!DOCTYPE html>
<html>
    <head>          
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Example株式会社 勤怠管理システム</title> 
    <link rel="stylesheet" href="../style2.css"/>
    </head>
    <body>
        <div id="wrapper">
          <header class="header"> 
            <h1 class="logo">
              <a href="/">Example</a>
            </h1>
                <nav class="global-nav">
                    <ul>
                      <li class="nav-item active"><a href="login-index.php">ログイン</a></li>
                      <li class="nav-item"><a href="join.php">新規登録</a></li>
                      <li class="nav-item"><a href="delete.php">登録削除</a></li>
                    </ul>
                </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">Example株式会社</p>
              <h1 class="site-title">勤怠管理システム ログイン</h1>
          </div>
          <div class="login-wrapper">
            <form action="" method="post">
                <p>ID</p>
                <input class="inputstyle" type="text" name="loginId" value="<?php print(htmlspecialchars($loginId, ENT_QUOTES)); ?>" >
                <?php if ($error['loginId'] === 'blank'): ?>
                <p class="error">*ＩＤを記入してください</p>
                <?php endif; ?>
                <?php if ($error['login'] === 'failed'): ?>
                <p class="error">*ログインに失敗しました</p>
                <?php endif; ?>
                <p>パスワード</p>
                <input class="inputstyle" type="password" name="password" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>"><br>
                <?php if ($error['password'] === 'blank'): ?>
                <p class="error">*パスワードを記入してください</p>
                <?php endif; ?>
                <?php if ($error['login'] === 'failed'): ?>
                <p class="error">*ログインに失敗しました</p>
                <?php endif; ?>
                <input id="" type="checkbox" name="save" value="on">
                <label for="save">次回からは自動的にログインする</label>
                <input type="submit" value="ログイン">
            </form>
        </div>
        <footer>    
            <p>Example Co. Ltd.</p>
        </footer>
    </body>
</html>             
