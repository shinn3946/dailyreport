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

if (!isset($_COOKIE['userId'])) {
    header('Location: ../login/login-index.php');
    exit();
}

if (!isset($_SESSION['new-report'])) {
    header('Location: new-report-index.php');
    exit();
}

if (!empty($_POST)) {
    $date = date("Y-m-d H:i:s");
    $statement = $db->prepare('INSERT INTO report SET day=?, tit=?, report=?, tim=?, id=?');
    $statement->execute(array(
        $_SESSION['new-report']['date'],
        $_SESSION['new-report']['tit'],
        $_SESSION['new-report']['report'],
        $date,
        $_COOKIE['userId']));
        unset($_SESSION['new-report']);
        header('Location: new-report-finish.php');
    exit();
}

?>

<!DOCTYPE html>
<html>
    <head>          
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Example株式会社 勤怠管理システム</title> 
    <link rel="stylesheet" href="../style.css"/>
    </head>
    <body>
        <div id="wrapper">
          <header class="header"> 
            <h1 class="logo">
              <a href="/">Example</a>
            </h1>
            <nav class="global-nav">
              <ul>
                <li class="nav-item"><a href="../main-index.php">HOME</a></li>
                <li class="nav-item"><a href="../member/member-index.php">メンバー管理</a></li>
                <li class="nav-item"><a href="../data/data-index.php">データ取得</a></li>
                <li class="nav-item"><a href="../report/report-index.php">日報一覧</a></li>
                <li class="nav-item"><a href="../attendance/attendance-view-index.php">勤怠一覧</a></li> 
                <li class="nav-item"><a href="../logout.php">ログアウト</a></li>
                <li class="nav-item username"><a><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>さん</a></li>
              </ul>
            </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">日報内容確認</p>
          </div>
          <div class="join-wrapper">
                <form action="" method="post">
                    <input type="hidden" name="action" value="submit"/>    
                        <p>日付</p>
                        <?php print(htmlspecialchars($_SESSION['new-report']['date'], ENT_QUOTES)); ?>    
                        <p>氏名</p>
                        <?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>    
                        <p>タイトル</p>
                        <?php print(htmlspecialchars($_SESSION['new-report']['tit'], ENT_QUOTES)); ?>   
                        <p>内容</p>
                     <?php print(htmlspecialchars($_SESSION['new-report']['report'], ENT_QUOTES)); ?><br>                  
                    <a href="new-report-index.php?action=rewrite">&laquo;&nbsp;中止する</a> | <input type="submit" value="登録する" />
              </form>  
              </div>
          <footer>    
            <p>Example Co. Ltd.</p>
          </footer>    
    </body>
</html>         