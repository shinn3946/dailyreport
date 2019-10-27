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

if (!isset($_SESSION['memberIndex'])) {
    header('Location: ../login/login-index.php');
    exit();
}

$member = $db->prepare('SELECT * FROM member WHERE id=?');
$member->execute(array(
$_COOKIE['userId']
));
$memberEditHolder = $member->fetch();

if (!empty($_POST['check'])) {
    if ($_POST['nam'] === '') {
    $error['nam'] = 'blank';   
     }
    if ($_POST['kana'] === '') {
    $error['kana'] = 'blank';   
     }
    if (!preg_match('/\A[a-z\d]{4,8}+\z/i', $_POST['log'], $data1)) {
    $error['log'] = 'length';      
     }
    if (!preg_match('/\A[a-z\d]{8,16}+\z/i', $_POST['pass'], $data2)) {
    $error['pass'] = 'length';      
     } 

    if(empty($error)) {
    $_SESSION['memberEdit'] = $_POST;    
    header('Location: memberUpdateCheck.php');
    exit();
    }
}

if (!empty($_POST['delete'])) {
    $_SESSION['memberEdit'] = $memberEditHolder;    
    header('Location: memberDeleteCheck.php');
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
                <li class="nav-item"><a href="member-index.php">メンバー管理</a></li>
                <li class="nav-item"><a href="../data/data-index.php">データ取得</a></li>
                <li class="nav-item"><a href="../report/report-index.php">日報一覧</a></li>
                <li class="nav-item"><a href="../attendance/attendance-view-index.php">勤怠一覧</a></li> 
                <li class="nav-item"><a href="../main/logout.php">ログアウト</a></li>
                <li class="nav-item username"><a><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>さん</a></li>
              </ul>
            </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">メンバー編集・削除</p>
          </div>
          <div class="join-wrapper">
                <form action="" method="post" enctype="plain/text">
                    <p>氏名</p>
                    <input type="text" name="nam" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['nam'], ENT_QUOTES)); ?>" placeholder="修正前：<?php print(htmlspecialchars($memberEditHolder['nam'], ENT_QUOTES)); ?>">
                    <?php if ($error['nam'] === 'blank'): ?>
                    <p class="error">*氏名を入力してください</p>
                    <?php endif; ?>
                    <p>ふりがな</p>
                    <input type="text" name="kana" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['kana'], ENT_QUOTES)); ?>" placeholder="修正前：<?php print(htmlspecialchars($memberEditHolder['kana'], ENT_QUOTES)); ?>">
                    <?php if ($error['kana'] === 'blank'): ?>
                    <p class="error">*ふりがなを入力してください</p>
                    <?php endif; ?>             
                    <p>区分</p>
                    <input type="radio" name="pos" value="社員">社員 
                    <input type="radio" name="pos" value="パート">パート
                    <input type="radio" name="pos" value="派遣" checked="checked">派遣
                    <p>ID</p>
                    <input type="text" name="log" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['log'], ENT_QUOTES)); ?>" placeholder="修正前：<?php print(htmlspecialchars($memberEditHolder['log'], ENT_QUOTES)); ?>">            
                    <?php if ($error['log'] === 'length'): ?>
                    <p class="error">*４〜８文字の英数字を入力してください</p>
                    <?php endif; ?>  
                    <p>パスワード</p> 
                    <input type="password" name="pass" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['pass'], ENT_QUOTES)); ?>" placeholder="修正前：********"><br> 
                    <?php if ($error['pass'] === 'length'): ?>
                    <p class="error">*８〜１６文字の英数字を入力してください</p>
                    <?php endif; ?>
                    <a href="member-index.php">中止する</a> | <input type="submit" name="check" value="編集する" /> | <input type="submit" name="delete" value="削除する" />
                </form>
            </div>
          <footer>    
            <p>Example Co. Ltd.</p>
          </footer>
        </div>    
    </body>
</html>           