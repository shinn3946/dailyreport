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

if (!isset($_SESSION['reportIndex'])) {
    header('Location: ../login/login-index.php');
    exit();
}

if (!empty($_POST['check'])) {
    if ($_POST['reportId'] === '') {
    $error['reportId'] = 'blank';   
     }
    if ($_POST['date'] === '') {
    $error['date'] = 'blank';   
     }
     if (!preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $_POST['date'])) {
        $error['date'] = 'length';      
         } 
     if ($_POST['date'] === '') {
        $error['date'] = 'blank';   
     }
     if ($_POST['report'] === '') {
        $error['report'] = 'blank';   
     }

    if(empty($error)) {
    $_SESSION['reportEdit'] = $_POST;    
    header('Location: reportUpdateCheck.php');
    exit();
    }
}

if (!empty($_POST['delete'])) {
    if ($_POST['reportId'] === '') {
        $error['reportId'] = 'blank';   
         }
        if ($_POST['date'] === '') {
        $error['date'] = 'blank';   
         }
         if (!preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $_POST['date'])) {
            $error['date'] = 'length';      
             } 
         if ($_POST['date'] === '') {
            $error['date'] = 'blank';   
         }
         if ($_POST['report'] === '') {
            $error['report'] = 'blank';   
         }
    
    if (empty($error)) {
    $_SESSION['reportEdit'] = $_POST;    
    header('Location: reportDeleteCheck.php');
    exit();
    }
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
              <p class="site-title-sub">日報編集・登録</p>
          </div>
          <div class="join-wrapper">   
                <form action="" method="post" enctype="plain/text">
                        <p>レポートＩＤ</p>
                        <input type="text" name="reportId" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['reportId'], ENT_QUOTES)); ?>" placeholder="記入例：1">
                        <?php if ($error['reportId'] === 'blank'): ?>
                        <p class="error">*レポートＩＤを入力してください</p>
                        <?php endif; ?>
                        <p>日付</p>
                        <input type="text" name="date" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['date'], ENT_QUOTES)); ?>" placeholder="記入例：2019/09/29">
                        <?php if ($error['date'] === 'blank'): ?>
                        <p class="error">*日付を入力してください</p>
                        <?php endif; ?>
                        <?php if ($error['date'] === 'length'): ?>
                        <p class="error">*YYYY/MM/DDのように入力してください</p>
                        <?php endif; ?> 
                        <p>氏名</p>
                        <p><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?></p>
                        <p>タイトル</p>
                        <input type="text" name="tit" size="" maxlength="16" value="<?php print(htmlspecialchars($_POST['tit'], ENT_QUOTES)); ?>" placeholder="記入例：今日の進捗">            
                        <?php if ($error['tit'] === 'blank'): ?>
                        <p class="error">*タイトルを入力してください</p>
                        <?php endif; ?>
                        <p>内容</p> 
                        <textarea name="report" rows="8" cols="60" maxlength="255" value="<?php print(htmlspecialchars($_POST['report'], ENT_QUOTES)); ?>"></textarea><br> 
                        <?php if ($error['report'] === 'blank'): ?>
                        <p class="error">*内容を入力してください</p>
                        <?php endif; ?>
                        <a href="report-index.php">中止する</a> | <input type="submit" name="check" value="変更する" /> | <input type="submit" name="delete" value="削除する" />
                    </form>
                </div>
          <footer>    
            <p>Example Co. Ltd.</p>
          </footer>
        </div>    
    </body>
</html>  
