<?php
header('content-type:text/html;charset=utf-8');
include 'function/function.php';
include 'controller/conclusionSubjectData.php';
include 'controller/client_estimate_process.php';
include 'function/crypt1.php';

$test = new conclusionSubjectData();

$test1 = new client_estimate_process();

$sysCmd->run_port_for_db();

$content = $test1->check_estimate_post_status();



/*$text['estimate_history'] = $content;

echo $back = curl_POST($text,"xmg520.cn/xz/server_estimate_index.php");
if(empty($back)){exit;}
$backs = explode(';',$back);
foreach($backs as $value){

    $value1 = explode(':',$value);

    if(!empty($value1[0])){
        $classInfoId = $value1[0];
        $stat = $value1[1];
        if(!empty($stat) and $stat=='yes' ){
            $sql = "update estimate_history set `post`='yes' where classInfoId='$classInfoId'";

            $db_res->query_num($sql);
        }
    }

}*/


?>

