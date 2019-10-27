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

if (!isset($_SESSION['reportEdit'])) {
    header('Location: ../login/login-index.php');
    exit();
}

if (!empty($_POST)) {
    $account = $db->prepare('UPDATE report SET day=?, tit=?, report=? WHERE reportId=?');
    $account->execute(array(
        $_SESSION['reportEdit']['date'],
        $_SESSION['reportEdit']['tit'], 
        $_SESSION['reportEdit']['report'],
        $_SESSION['reportEdit']['reportId']   
    ));
    header('Location: reportUpdateFinish.php');
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
                <li class="nav-item"><a href="../main/main-index.php">HOME</a></li>
                <li class="nav-item"><a href="../member/member-index.php">メンバー管理</a></li>
                <li class="nav-item"><a href="../data/data-index.php">データ取得</a></li>
                <li class="nav-item"><a href="report-index.php">日報一覧</a></li>
                <li class="nav-item"><a href="../attendance/attendance-view-index.php">勤怠一覧</a></li> 
                <li class="nav-item"><a href="../main/logout.php">ログアウト</a></li>
                <li class="nav-item username"><a><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>さん</a></li>
              </ul>
            </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">編集内容確認</p>
          </div>
          <div class="top-wrapper">
                <h1>修正内容確認</h1>
            </div>
            <div class="join-wrapper">
            <form action="" method="post">
                <input type="hidden" name="action" value="submit"/>
                 <p>レポートＩＤ</p>
                    <?php print(htmlspecialchars($_SESSION['reportEdit']['reportId'], ENT_QUOTES)); ?>    
                    <p>日付</p>
                    <?php print(htmlspecialchars($_SESSION['reportEdit']['date'], ENT_QUOTES)); ?>    
                    <p>氏名</p>
                    <?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>    
                    <p>タイトル</p>
                    <?php print(htmlspecialchars($_SESSION['reportEdit']['tit'], ENT_QUOTES)); ?>   
                    <p>内容</p>
                 <?php print(htmlspecialchars($_SESSION['reportEdit']['report'], ENT_QUOTES)); ?><br>    
                <a href="report-edit.php?action=rewrite">&laquo;&nbsp;中止する</a> | <input type="submit" value="編集する" />
          </form>  
          </div>
          <footer>    
            <p>Example Co. Ltd.</p>
          </footer>
        </div>    
    </body>
</html>  