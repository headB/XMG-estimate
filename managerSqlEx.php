<?php 
header('content-type:html/text;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
include 'function/function.php';
include 'exec_auth.php';

//Asia/Shanghai


if(isset($_GET['id'])and $_GET['id']!="" and isset($_GET['port']) and $_GET['port']!="")
{$id=$_GET['id'];
$name=$_GET['name'];
$class=$_GET['class'];
$time = $_GET['time'];
       $downTypeId = GET_values('typeDetailId');
       $time = date('YmdHi',strtotime($time));
$port = $_GET['port'];
       $downTypeDetail = $db_res->getRnamebyPortId($downTypeId);

$output =  "------广州小码哥行政制作-导出数据--\r\n评价起始时间$time";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:$port/grade/download-$downTypeDetail?id=$id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$output.= curl_exec($ch);
curl_close($ch);
}

$filename="$name-$class$time.txt";

 
$encoded_filename = urlencode($filename);  
$filename = $encoded_filename = str_replace("+", "%20", $encoded_filename);  


$chars = $output; //需要导出的文件的内容

header('Content-Type: text/x-sql');

header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

header('Content-Disposition: attachment; filename="' .$filename. '"');



       header('Pragma: no-cache');

       header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

   

echo $chars;

exit();




?>