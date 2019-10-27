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
            $_SESSION['delete'] = $member; 
            header('Location: deleteCheck.php');
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
                      <li class="nav-item"><a href="login-index.php">ログイン</a></li>
                      <li class="nav-item"><a href="join.php">新規登録</a></li>
                      <li class="nav-item active"><a href="delete.php">登録削除</a></li>
                    </ul>
                </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">登録削除</p>
          </div>
          <div class="login-wrapper">
                <form action="" method="post">
                        <p>ID</p>
                        <input type="text" name="loginId" value="<?php print(htmlspecialchars($loginId, ENT_QUOTES)); ?>" >
                        <?php if ($error['loginId'] === 'blank'): ?>
                        <p class="error">*ＩＤを記入してください</p>
                        <?php endif; ?>
                        <?php if ($error['login'] === 'failed'): ?>
                        <p class="error">*入力に誤りがあります</p>
                        <?php endif; ?>
                        <p>パスワード</p>
                        <input type="password" name="password" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>"><br>
                        <?php if ($error['password'] === 'blank'): ?>
                        <p class="error">*パスワードを記入してください</p>
                        <?php endif; ?>
                        <?php if ($error['login'] === 'failed'): ?>
                        <p class="error">*入力に誤りがあります</p>
                        <?php endif; ?>
                        <input type="submit" value="削除する">
                    </form>
                </div>
        <footer>    
            <p>Example Co. Ltd.</p>
        </footer>
    </body>
</html>        
