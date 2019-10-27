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

$sql = $db->prepare('select * from member');
$sql->execute();

if(isset($_POST['edi'])) {
    $_SESSION['memberIndex'] = 1;
    header('Location:member-edit.php');
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
              <a href="/">GLAND LIFE</a>
            </h1>
            <nav class="global-nav">
              <ul>
                <li class="nav-item"><a href="../main/main-index.php">HOME</a></li>
                <li class="nav-item active"><a href="member-index.php">メンバー管理</a></li>
                <li class="nav-item"><a href="../data/data-index.php">データ取得</a></li>
                <li class="nav-item"><a href="../report/report-index.php">日報一覧</a></li>
                <li class="nav-item"><a href="../attendance/attendance-view-index.php">勤怠一覧</a></li> 
                <li class="nav-item"><a href="../main/logout.php">ログアウト</a></li>
                <li class="nav-item username"><a><?php print(htmlspecialchars($_COOKIE['username'], ENT_QUOTES)); ?>さん</a></li>
              </ul>
            </nav>
        　</header>
          <div class="top-wrapper">
              <p class="site-title-sub">メンバー管理</p>
          </div>
          <div class="view-wrapper">   
            <table>
                <tbody>
                    <tr>
                        <th>ＩＤ</th>
                        <th>氏名</th>
                        <th>ふりがな</th>
                        <th>区分</th>
                        <th>編集・削除</th>
                    </tr>
                    <?php while($members = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <th><?php print(htmlspecialchars($members['log'])); ?></th>
                        <th><?php print(htmlspecialchars($members['nam'])); ?></th>
                        <th><?php print(htmlspecialchars($members['kana'])); ?></th>
                        <th><?php print(htmlspecialchars($members['pos'])); ?></th>
                        <th><?php if ($members['id'] == $_COOKIE['userId']): ?><form action="" method="post"><input type="submit" name="edi" value="編集画面へ"></form><?php endif; ?></th>
                    </tr>
                    <?php } ?>    
                </tbody>
            </table>   
        </div>
          <footer>    
            <p>Example Co. Ltd.</p>
          </footer>
        </div>    
    </body>
</html>           

                        
