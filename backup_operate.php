<?php
header('content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
$time = date("Y-m-d");

include 'conn.php';
include 'function/function.php';
echo $time;
echo "<br>";
if(isset($_POST['syn_code']))
{
    if($_POST['syn_code']=='xxx234245fKJHOIH234!2$##')
    {

    }
    else{
        echo "error";exit;
    }

}
else
    {
    echo "error";exit;
    }

$info = $_POST;


$i=0;
foreach($info as $value)
{

    if(isset($value['time']))
    {
//这个位置需要根据不同的站点设置不同的信息
            if(!isset($value['f_is_record']))
            {

                $sql = addslashes($value['sql']);
                $time_sql = $value['time'];
                $who =$value['who'];
                echo $time_sql."<br>";
                $res = $conn->query("insert into rentlist_operate_history(`sql`,`time`,`who`) VALUES('$sql','$time_sql','$who')");
                $num = $conn->affected_rows;
                echo $num;
                if($num=='1')
                {
                    echo "client_success!"."<br>";
                    $id = $value['id'];
                    $back_to_server[] = array("id"=>"$id");
                    echo $i."<br>";
                    $i++;
                }
                else
                {
                    echo 'client_failed!'."<br>";
                    //储存已经成功的名单加入命令准备用于回应给服务端
                }
            }

        }
    //这个空间位置不要放任何的程序在这里！！
}
if(!empty($back_to_server))
{
    //这里也是根据不同的站点设置不用的提交信息
    $back_to_server['website'] = 'f';
    echo response_url($back_to_server, "xmg520.com/xz/backup_confirm.php");
}
else
{
    echo "cancel send message to server";exit;
}


?>