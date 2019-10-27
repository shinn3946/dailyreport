<?php
session_start();
// エスケープ処理
require_once('../security/escape.php');
// パスワードのハッシュ化
require_once('../vendor/ircmaxell/password-compat/lib/password.php');
// クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

$dbname="dailyreport";
$dbhost="localhost";
$dsn="mysql:dbname={$dbname};host={$dbhost}";
$dbuser="dailyreport";
$dbpassword="48FX3tv3%";

if(isset($_POST['atte'])) {
    $file_path_att = "attendance.csv";
    $export_csv_title_att = [ "勤怠ＩＤ", "氏名", "ふりがな", "年", "月", "日", "出勤時刻" ];
    $export_sql_att = "select attenId, nam, kana, year, month, day, att from attendance left join member on attendance.id = member.id";
    $afterConvert = "SJIS-win";
    $beforeCOnvert = "UTF-8";
    foreach($export_csv_title_att as $key => $val_att) {
        mb_convert_variables($afterConvert, $beforeConvert, $val_att);
        $export_header_att = $export_csv_title_att; 
    }
    try {
        $dbh = new PDO($dsn, $dbuser, $dbpassword); 
    }catch(PDOException $e){
        print('Connection failed:'.$e->getMessage());
        die();
    }
    if(touch($file_path_att)) {
        $file_att = new SplFileObject($file_path_att, "w");
        $file_att->fputcsv($export_header_att);
        $stmt_att = $dbh->query($export_sql_att);
        while($row_att = $stmt_att->fetch(PDO::FETCH_ASSOC)){
            mb_convert_variables($afterConvert, $beforeConvert, $stmt_att);
            $file_att->fputcsv($row_att);
        }
        $dbh = null;
        header('Content-Type: application/octet-stream');
        header('Content-Length: '.filesize($file_path_att));
        header('Content-Disposition: attachment; filename=' . $file_path_att);
        readfile($file_path_att);
        exit;
    }
}

if(isset($_POST['leav'])) {
    $file_path_lea = "leave.csv";
    $export_csv_title_lea = ["勤怠ＩＤ", "氏名", "ふりがな", "年", "月", "日", "退勤時刻"];
    $export_sql_lea = "select attenId, nam, kana, year, month, day, lea from attendance left join member on attendance.id = member.id";
    $afterConvert = "SJIS-win";
    $beforeCOnvert = "UTF-8";
    foreach($export_csv_title_lea as $key => $val_lea) {
        mb_convert_variables($afterConvert, $beforeConvert, $stmt_lea);
        $export_header_lea = $export_csv_title_lea;
    }    
    try {
        $dbh = new PDO($dsn, $dbuser, $dbpassword); 
    }catch(PDOException $e){
        print('Connection failed:'.$e->getMessage());
        die();
    }
if(touch($file_path_lea)) {
    $file_lea = new SplFileObject($file_path_lea, "w");
    $file_lea->fputcsv($export_header_lea);
    $stmt_lea = $dbh->query($export_sql_lea);
    while($row_lea = $stmt_lea->fetch(PDO::FETCH_ASSOC)){
        mb_convert_variables($afterConvert, $beforeConvert, $stmt_lea);
        $file_lea->fputcsv($row_lea);
        }
    $dbh = null;
    header('Content-Type: application/octet-stream');
    header('Content-Length: '.filesize($file_path_lea));
    header('Content-Disposition: attachment; filename=' .$file_path_lea);
    readfile($file_path_lea);
    exit;
    }
}

if(isset($_POST['memb'])) {
    $file_path_mem = "member.csv";
    $export_csv_title_mem = ["ＩＤ", "氏名", "ふりがな", "区分"];
    $export_sql_mem = "select log, nam, kana, pos from member";
    $afterConvert = "SJIS-win";
    $beforeCOnvert = "UTF-8";
    foreach($export_csv_title_mem as $key => $val_mem) {
        mb_convert_variables($afterConvert, $beforeConvert, $stmt_mem);
        $export_header_mem = $export_csv_title_mem;
    }
    try {
        $dbh = new PDO($dsn, $dbuser, $dbpassword); 
    }catch(PDOException $e){
        print('Connection failed:'.$e->getMessage());
        die();
    }
    if(touch($file_path_mem)) {
        $file_mem = new SplFileObject($file_path_mem, "w");
        $file_mem->fputcsv($export_header_mem);
        $stmt_mem = $dbh->query($export_sql_mem);
        while($row_mem = $stmt_mem->fetch(PDO::FETCH_ASSOC)){
            mb_convert_variables($afterConvert, $beforeConvert, $stmt_mem);
            $file_mem->fputcsv($row_mem);
            }
        $dbh = null;
        header('Content-Type: application/octet-stream');
        header('Content-Length: '.filesize($file_path_mem));
        header('Content-Disposition: attachment; filename=member' . $file_path_mem);
        readfile($file_path_mem);
        exit;
        }
}

?>