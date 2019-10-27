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

// 未入力や文字数範囲外がある場合に警告文を出す
if (!empty($_POST)) {
    if ($_POST['name'] === '') {
    $error['name'] = 'blank';   
     }
    if ($_POST['hurigana'] === '') {
    $error['hurigana'] = 'blank';   
     }
    if (!preg_match('/\A[a-z\d]{4,8}+\z/i', $_POST['loginId'], $data1)) {
    $error['loginId'] = 'length';      
     }
    if (!preg_match('/\A[a-z\d]{8,16}+\z/i', $_POST['password'], $data2)) {
    $error['password'] = 'length';      
     } 

// 登録情報の重複チェック
    if(empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE name=?');
        $member->execute(array($_POST['name']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['name'] = 'dupicate';
        }
    }

    if(empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE hurigana=?');
        $member->execute(array($_POST['hurigana']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['hurigana'] = 'dupicate';
        }
    }

    if(empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE loginId=?');
        $member->execute(array($_POST['loginId']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['loginId'] = 'dupicate';
        }
    }


    if(empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE password=?');
        $member->execute(array($_POST['password']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['password'] = 'dupicate';
        }
    }
    if(empty($error)) {
    $_SESSION['join'] = $_POST;    
    header('Location: check.php');
    exit();
    }
}

if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
    $_POST = $_SESSION['join'];
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
                      <li class="nav-item active"><a href="join.php">新規登録</a></li>
                      <li class="nav-item"><a href="delete.php">登録削除</a></li>
                    </ul>
                </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">新規登録</p>
          </div>
          <div class="join-wrapper">
                <form action="" method="post" enctype="plain/text">
                    <p>氏名</p>
                    <input type="text" name="name" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" placeholder="例：山田太郎">
                    <?php if ($error['name'] === 'blank'): ?>
                    <p class="error">*氏名を入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['name'] === 'duplicate'): ?>
                    <p class="error">*その氏名は既に登録されています</p>
                    <?php endif; ?> 
                    <p>ふりがな</p>
                    <input type="text" name="hurigana" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['hurigana'], ENT_QUOTES)); ?>" placeholder="例：やまだたろう">
                    <?php if ($error['hurigana'] === 'blank'): ?>
                    <p class="error">*ふりがなを入力してください</p>
                    <?php endif; ?>             
                    <?php if ($error['hurigana'] === 'duplicate'): ?>
                    <p class="error">*そのふりがなは既に登録されています</p>
                    <?php endif; ?> 
                    <p>区分</p>
                    <input type="radio" name="position" value="社員">社員 
                    <input type="radio" name="position" value="パート">パート
                    <input type="radio" name="position" value="派遣" checked="checked">派遣
                    <p>ID</p>
                    <input type="text" name="loginId" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['loginId'], ENT_QUOTES)); ?>" placeholder="４〜８文字の半角英数字">            
                    <?php if ($error['loginId'] === 'length'): ?>
                    <p class="error">*４〜８文字の英数字を入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['loginId'] === 'duplicate'): ?>
                    <p class="error">*そのＩＤは既に登録されています</p>
                    <?php endif; ?>
                    <p>パスワード</p> 
                    <input type="password" name="password" size="" maxlength="" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" placeholder="８〜１６文字の半角英数字"><br> 
                    <?php if ($error['password'] === 'length'): ?>
                    <p class="error">*８〜１６文字の英数字を入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['password'] === 'duplicate'): ?>
                    <p class="error">*そのパスワードは既に登録されています</p>
                    <?php endif; ?> 
                    <input type="submit" value="新規登録">
                </form>
            </div>
          
        <footer>    
            <p>Example Co. Ltd.</p>
        </footer>
    </body>
</html>  