<?php

include 'db.php';

function curl($url){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$output = curl_exec($ch);
curl_close($ch);
    return $output;
}

date_default_timezone_set('Asia/Shanghai');

$today = date('Y-m-d',time());

$db = new db();

$ifExist = $db->getValuesById($today,'backupsql','date');

if(!empty($ifExist['values'])){
    exit;
}

$fileName = curl("http://xmg520.com/xz/index.php?r=admin/dbback/backup");

$result = curl("http://xmg520.com/xz/index.php?r=admin/dbback/downloadBackupFile&request=xiazaiwenjian&fn=$fileName");

$dirname = __DIR__.DIRECTORY_SEPARATOR."backupSql".DIRECTORY_SEPARATOR.$today."backupsql.php";

file_put_contents($dirname,$result);

if(file_exists($dirname)){

    $db->query_num("insert into backupsql(`date`,`filename`) values('$today','$dirname')");

}



?>