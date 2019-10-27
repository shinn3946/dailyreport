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

$attCount = 0;
$leaCount = 0;

if (isset($_POST['ans']) && $_COOKIE['attenId'] > 0) {
    $attCount++;
    if($attCount == 1) {
        $_SESSION['main']['att'] = date("Y-m-d H:i:s");
        $_SESSION['main']['year'] = date('Y');
        $_SESSION['main']['month'] = date('m');
        $_SESSION['main']['day'] = date('d');
        $_SESSION['main']['week'] = date('w');
        $_SESSION['main']['id'] = $_COOKIE['userId'];
        $attSql = $db->prepare('INSERT INTO attendance(att, year, month, day, week, id) VALUES(:att, :year, :month, :day, :week, :id)');
        $attSql->bindValue(':att', $_SESSION['main']['att']);
        $attSql->bindValue(':year', $_SESSION['main']['year']);
        $attSql->bindValue(':month', $_SESSION['main']['month']);
        $attSql->bindValue(':day', $_SESSION['main']['day']);
        $attSql->bindValue(':week', $_SESSION['main']['week']);
        $attSql->bindValue(':id', $_SESSION['main']['id']);
        $attSql->execute();
        unset($_SESSION['main']);
            $attId = $db->prepare('SELECT * FROM attendance WHERE id=?');
            $attId->execute(array(
                $_COOKIE['userId']));
            $a = $attId->fetch();
            setcookie('attenId', $a['attenId'], time()+60*60*12);
            setcookie('attCount', 1, time()+60*60*12); 
            unset($_POST['ans']);          
    }
}

if (isset($_POST['che'])) {
    $leaCount++;
    if($_COOKIE['attCount'] == 1 && $leaCount == 1) {
        $_SESSION['main']['lea'] = date("Y-m-d H:i:s");
        $leaSql = "UPDATE attendance SET lea = :lea WHERE id = :id";
        $leaId = $db->prepare($leaSql);
        $params = array(':lea' => $_SESSION['main']['lea'], 'id' => $_COOKIE['userId']);
        $leaId->execute($params);
        unset($_SESSION['main']);
        unset($_COOKIE['attenId']);
        unset($_COOKIE['attCount']);
        unset($_COOKIE['userId']);
        unset($_COOKIE['username']);
        header('Location:../login/login-index.php');
        exit();  
    }
}

if(isset($_POST['new'])) {
    header('Location:../new-report/new-report-index.php');
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
                <li class="nav-item active"><a href="main-index.php">HOME</a></li>
                <li class="nav-item"><a href="../member/member-index.php">メンバー管理</a></li>
                <li class="nav-item"><a href="../data/data-index.php">データ取得</a></li>
                <li class="nav-item"><a href="../report/report-index.php">日報一覧</a></li>
                <li class="nav-item"><a href="../attendance/attendance-view-index.php">勤怠一覧</a></li> 
                <li class="nav-item"><a href="logout.php">ログアウト</a></li>
                <li class="nav-item username"><a><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>さん</a></li>
              </ul>
            </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">Example株式会社</p>
              <h1 class="site-title">勤怠管理システム</h1>
          </div>
          <div class="main-wrapper">   
            <form class="buttons" action="" method="post">
              <input class="button" type="submit" name="ans" value="出勤">
              <input class="button yellow" type="submit" name="che" value="退勤">
              <input class="button orange" type="submit" name="new" value="新規日報の登録">
             </form>
          </div>
          <footer>    
            <p>Example Co. Ltd.</p>
          </footer>
        </div>    
    </body>
</html>             