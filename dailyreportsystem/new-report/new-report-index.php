<?php
session_start();
// エスケープ処理
require_once('../security/escape.php');
// パスワードのハッシュ化
// require_once('../vendor/ircmaxell/password-compat/lib/password.php');
// クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
// データベースに接続
require_once('../dbconnect.php');

if (!isset($_COOKIE['userId'])) {
    header('Location: ../login/login-index.php');
    exit();
}

// 未入力や文字数範囲外がある場合に警告文を出す
if (!empty($_POST)) {
    if ($_POST['date'] === '') {
    $error['date'] = 'blank';   
     }
    if (!preg_match('/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $_POST['date'])) {
    $error['date'] = 'length';      
     } 
    if ($_POST['tit'] === '') {
    $error['tit'] = 'blank';   
     }
    if ($_POST['report'] === 'report') {
    $error['report'] = 'blank';      
     }

 // 登録情報の重複チェック
    if(empty($error)) {
        $report = $db->prepare('SELECT COUNT(*) AS cnt FROM report WHERE date=?');
        $report->execute(array($_POST['date']));
        $record = $report->fetch();
        if ($record['cnt'] > 0) {
            $error['date'] = 'dupicate';
        }
    }

    if(empty($error)) {
    $_SESSION['new-report'] = $_POST;    
    header('Location: new-report-check.php');
    exit();
    }
}

if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['new-report'])) {
    $_POST = $_SESSION['new-report'];
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
              <p class="site-title-sub">日報登録</p>
          </div>
          <div class="join-wrapper">   
            　<form action="" method="post" enctype="plain/text">
                <p>日付</p>
                <input type="text" name="date" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['date'], ENT_QUOTES)); ?>" placeholder="例：YYYY/MM/DD">
                <?php if ($error['date'] === 'blank'): ?>
                <p class="error">*日付を入力してください</p>
                <?php endif; ?>
                <?php if ($error['date'] === 'length'): ?>
                <p class="error">*YYYY/MM/DDのように入力してください</p>
                <?php endif; ?> 
                <?php if ($error['date'] === 'duplicate'): ?>
                <p class="error">*その日付で既に登録されています</p>
                <?php endif; ?> 
                <p>氏名</p>
                <p><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?></p>
                <p>タイトル</p>
                <input type="text" name="tit" size="" maxlength="16" value="<?php print(htmlspecialchars($_POST['tit'], ENT_QUOTES)); ?>" placeholder="例：今日の進捗">            
                <?php if ($error['tit'] === 'blank'): ?>
                <p class="error">*タイトルを入力してください</p>
                <?php endif; ?>
                <p>内容</p> 
                <textarea name="report" rows="8" cols="60" maxlength="255"></textarea><br> 
                <?php if ($error['report'] === 'blank'): ?>
                <p class="error">*内容を入力してください</p>
                <?php endif; ?>
                <input type="submit" value="投稿">
            </form>
        </div>    
          <footer>    
            <p>Example Co. Ltd.</p>
          </footer>    
    </body>
</html>         