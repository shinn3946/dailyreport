<?php 
try {
    $db = new PDO('mysql:dbname=dailyreport; host=localhost; charset=utf8', 'dailyreport', '48FX3tv3%');
} catch(PDOException $e) {
    print('DB接続エラー：' . $e->getMessage());
}    
