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

if (!empty($_POST)) {
    $account = $db->prepare('DELETE FROM attendance WHERE attenId=?');
    $account->execute(array(
        $_SESSION['attendanceEdit']['attenId']  
    ));
    header('Location: attendanceDeleteFinish.php');
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
                <li class="nav-item"><a href="../report/report-index.php">日報一覧</a></li>
                <li class="nav-item"><a href="attendance-view-index.php">勤怠一覧</a></li> 
                <li class="nav-item"><a href="../main/logout.php">ログアウト</a></li>
                <li class="nav-item username"><a><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>さん</a></li>
              </ul>
            </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">勤怠削除確認</p>
          </div>
          <div class="join-wrapper">
                <form action="" method="post">
                    <input type="hidden" name="action" value="submit"/>    
                        <p>勤怠ＩＤ</p>
                        <?php print(htmlspecialchars($_SESSION['attendanceEdit']['attenId'], ENT_QUOTES)); ?>    
                     <p>氏名</p>
                     <?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>   
                     <p>年</p>
                        <?php print(htmlspecialchars($_SESSION['attendanceEdit']['year'], ENT_QUOTES)); ?>       
                     <p>月</p>
                     <?php print(htmlspecialchars($_SESSION['attendanceEdit']['month'], ENT_QUOTES)); ?>
                     <p>日</p>
                     <?php print(htmlspecialchars($_SESSION['attendanceEdit']['day'], ENT_QUOTES)); ?>
                     <p>曜日</p>
                        <?php print(htmlspecialchars($_SESSION['attendanceEdit']['week'], ENT_QUOTES)); ?>   
                     <p>出勤時刻</p>
                        <?php print(htmlspecialchars($_SESSION['attendanceEdit']['att'], ENT_QUOTES)); ?>   
                        <p>退勤時刻</p>
                     <?php print(htmlspecialchars($_SESSION['attendanceEdit']['lea'], ENT_QUOTES)); ?><br>  
                    <a href="attendance-edit.php?action=rewrite">&laquo;&nbsp;中止する</a> | <input type="submit" value="編集する" />
              </form>  
              </div>
          <footer>    
            <p>Example Co. Ltd.</p>
          </footer>
        </div>    
    </body>
</html>        