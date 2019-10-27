<?php
session_start();
// エスケープ処理
require_once('../security/escape.php');
// パスワードのハッシュ化
require_once('../vendor/ircmaxell/password-compat/lib/password.php');
// クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
// データベースに接続し、csvファイル出力
require_once('fputcsv.php');

if (!isset($_COOKIE['userId'])) {
    header('Location: ../login/login-index.php');
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
                <li class="nav-item active"><a href="data-index.php">データ取得</a></li>
                <li class="nav-item"><a href="../report/report-index.php">日報一覧</a></li>
                <li class="nav-item"><a href="../attendance/attendance-view-index.php">勤怠一覧</a></li> 
                <li class="nav-item"><a href="../main/logout.php">ログアウト</a></li>
                <li class="nav-item username"><a><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>さん</a></li>
              </ul>
            </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">Example株式会社</p>
              <h1 class="site-title">データ取得</h1>
          </div>
          <div class="main-wrapper">   
            <form class="buttons" action="" method="post">
              <input class="button" type="submit" name="atte" value="出勤データ">
              <input class="button yellow" type="submit" name="leav" value="退勤データ">
              <input class="button orange" type="submit" name="memb" value="メンバーデータ">
             </form>
          </div>
          <footer>    
            <p>Example Co. Ltd.</p>
          </footer>
        </div>    
    </body>
</html>    