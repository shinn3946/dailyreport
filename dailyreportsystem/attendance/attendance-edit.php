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

if (!isset($_SESSION['attendanceIndex'])) {
    header('Location: ../login/login-index.php');
    exit();
}

if (!empty($_POST['check'])) {
    if ($_POST['attenId'] === '') {
    $error['attenId'] = 'blank';   
     }
    if ($_POST['year'] === '') {
    $error['year'] = 'blank';   
     }
     if ($_POST['month'] === '') {
    $error['month'] = 'blank';   
     }
    if ($_POST['day'] === '') {
    $error['day'] = 'blank';   
     }    
     if ($_POST['week'] === '') {
    $error['week'] = 'blank';   
     }
     if ($_POST['att'] === '') {
    $error['att'] = 'blank';   
     }
    if ($_POST['lea'] === '') {
    $error['lea'] = 'blank';   
     }
    if(empty($error)) {
    $_SESSION['attendanceEdit'] = $_POST;    
    header('Location: attendanceUpdateCheck.php');
    exit();
    }
}

if (!empty($_POST['delete'])) {
    if ($_POST['attenId'] === '') {
        $error['attenId'] = 'blank';   
    }
 
    if (empty($error)) {
    $_SESSION['attendanceEdit'] = $_POST;    
    header('Location: attendanceDeleteCheck.php');
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
                <li class="nav-item"><a href="../report/report-index.php">日報一覧</a></li>
                <li class="nav-item"><a href="attendance-view-index.php">勤怠一覧</a></li> 
                <li class="nav-item"><a href="../main/logout.php">ログアウト</a></li>
                <li class="nav-item username"><a><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>さん</a></li>
              </ul>
            </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">勤怠編集・削除</p>
          </div>
          <div class="join-wrapper">
                <form action="" method="post">
                    <input type="hidden" name="action" value="submit"/>
                     <p>勤怠ＩＤ</p>
                     <input type="text" name="attenId" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['attenId'])) ?>" placeholder="記入例：1">
                     <?php if ($error['attenId'] === 'blank'): ?>
                     <p class="error">*勤怠ＩＤを入力してください</p>
                     <?php endif; ?>
                     <p>氏名</p>
                     <?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>
                     <p>年</p> 
                     <input type="text" name="year" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['year'], ENT_QUOTES)); ?>" placeholder="記入例：2019">
                     <?php if ($error['year'] === 'blank'): ?>
                     <p class="error">*何年かを入力してください</p>
                     <?php endif; ?>
                     <p>月</p> 
                     <input type="text" name="month" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['month'], ENT_QUOTES)); ?>" placeholder="記入例：09">   
                     <?php if ($error['month'] === 'blank'): ?>
                     <p class="error">*何月かを入力してください</p>
                     <?php endif; ?>
                     <p>日</p>
                     <input type="" name="day" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['day'], ENT_QUOTES)); ?>" placeholder="記入例：29">        
                     <?php if ($error['day'] === 'blank'): ?>
                     <p class="error">*何日かを入力してください</p>
                     <?php endif; ?>
                     <p>曜日（日から土で0〜6で入力）</p>
                     <input type="text" name="week" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['week'], ENT_QUOTES)); ?>" placeholder="記入例：日曜日なら0">   
                     <?php if ($error['week'] === 'blank'): ?>
                     <p class="error">*何曜日かを入力してください</p>
                     <?php endif; ?>
                     <p>出勤時刻</p>
                     <input type="text" name="att" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['att'], ENT_QUOTES)); ?>" placeholder="記入例：2019-09-27 13:00:00">     
                     <?php if ($error['att'] === 'blank'): ?>
                     <p class="error">*出勤時刻を入力してください</p>
                     <?php endif; ?>
                     <p>退勤時刻</p>
                     <input type="text" name="lea" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['lea'], ENT_QUOTES)); ?>" placeholder="記入例：2019-09-27 18:00:00"><br>          
                     <?php if ($error['lea'] === 'blank'): ?>
                     <p class="error">*退勤時刻を入力してください</p>
                     <?php endif; ?>
                    <a href="attendance-view-index.php">中止する</a> | <input type="submit" name="check" value="変更する" /> | <input type="submit" name="delete" value="削除する" />
              </form>  
              </div>
          <footer>    
            <p>Example Co. Ltd</p>
          </footer>
        </div>    
    </body>
</html>     