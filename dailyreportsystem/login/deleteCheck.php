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

if (!isset($_SESSION['delete'])) {
    header('Location: delete.php');
    exit();
}

if (!empty($_POST)) {
    $account = $db->prepare('DELETE FROM member WHERE log=?');
    $account->execute(array(
        $_SESSION['delete'][4]    
    ));
    unset($_SESSION['delete']);
    header('Location: deleteFinish.php');
    exit();
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
              <a href="/">GLAND LIFE</a>
            </h1>
                <nav class="global-nav">
                    <ul>
                      <li class="nav-item"><a href="login-index.php">ログイン</a></li>
                      <li class="nav-item"><a href="join.php">新規登録</a></li>
                      <li class="nav-item"><a href="delete.php">登録削除</a></li>
                    </ul>
                </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">登録削除確認</p>
          </div>
          <div class="join-wrapper">
                <form action="" method="post">
                    <input type="hidden" name="action" value="submit"/>    
                        <p>氏名</p>
                        <?php print(htmlspecialchars($_SESSION['delete'][1], ENT_QUOTES)); ?>    
                        <p>ふりがな</p>
                        <?php print(htmlspecialchars($_SESSION['delete'][2], ENT_QUOTES)); ?>    
                        <p>区分</p>
                        <?php print(htmlspecialchars($_SESSION['delete'][3], ENT_QUOTES)); ?>   
                        <p>ID</p>
                        <?php print(htmlspecialchars($_SESSION['delete'][4], ENT_QUOTES)); ?>
                        <p>パスワード</p> 
                        <p>********</p>    
                    <a href="delete.php?action=rewrite">&laquo;&nbsp;中止する</a> | <input type="submit" value="削除する" />
              </form>  
              </div>
        <footer>    
            <p>Example Co. Ltd.</p>
        </footer>
    </body>
</html>       