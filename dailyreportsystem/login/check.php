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

if (!isset($_SESSION['join'])) {
    header('Location: join.php');
    exit();
}

if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO member SET nam=?, kana=?, pos=?, log=?, pass=?');
    $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['hurigana'],
        $_SESSION['join']['position'],
        $_SESSION['join']['loginId'],
        $_SESSION['join']['password']));
    unset($_SESSION['join']);

    header('Location: finish.php');
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
              <a href="/">Example</a>
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
              <p class="site-title-sub">新規登録確認</p>
          </div>
          <div class="join-wrapper">
                <form action="" method="post">
                    <input type="hidden" name="action" value="submit"/>    
                        <p>氏名</p>
                        <?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>    
                        <p>ふりがな</p>
                        <?php print(htmlspecialchars($_SESSION['join']['hurigana'], ENT_QUOTES)); ?>    
                        <p>区分</p>
                        <?php print(htmlspecialchars($_SESSION['join']['position'], ENT_QUOTES)); ?>   
                        <p>ID</p>
                        <?php print(htmlspecialchars($_SESSION['join']['loginId'], ENT_QUOTES)); ?>
                        <p>パスワード</p> 
                        <p>********</p>    
                    <a href="join.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" />
              </form>  
            </div>
        <footer>    
            <p>Example Co. Ltd.</p>
        </footer>
    </body>
</html>        

