<?php
include('function/function.php');
header('content-type:text/html;charset=utf-8');
include('conn.php');
date_default_timezone_set('Asia/Shanghai');

$portTypeDetailId = POST_values('typeDetail');
$port = POST_values('typeDetail');
$place = POST_values('place');
$subject = POST_values('subject');


$port_check = $db_res->getValuesById($port,'port_type','id');
if(!empty($port_check)){

    $portTypeQuery = $db_res->getValuesById($port_check['values'][0]['tid'],'port_type','id');
    if(!empty($portTypeQuery)){
        $port = $portTypeQuery['values'][0]['port'];
        $port = (!empty($port_check['values'][0]['Rname']))?$port+1:$port;
        //还有这里，还需要一个用于当前端口是否可用的函数。
    }
} else{echo "error";exit;}


if(empty($port) or empty($_POST['user_name']) or empty($_POST['password']) or empty($place))
{
    stop_exec('评分请求提交失败，请检查参数','prepare_setting');
}

if(empty($subject) or $subject=='nodata'){
    throw_error_and_return('学科信息选择错误！');
}

    $u=$_POST['user_name'];
    $regexName = "#^[\x{4e00}-\x{9fa5}]{2,4}$#u";
    preg_match($regexName,$u,$u1);
         if(empty($u1[0]))
         {
             stop_exec('中文名字啊，难道已经有foreigner friends join 小码哥？？！','prepare_setting');
         }


     $u=$u1[0];
    $p=$_POST['password'];
    $teacherName = $post_data1['teacherName'] = addslashes($u);
    $className = $post_data1['className'] =  addslashes($p);

    $post_data = json_encode($post_data1);


session_start();
$temp_user = $_SESSION['xz_email'];
$res = $db_res->getValuesById($temp_user,'user','email');
$repeatOr = $db_res->getValuesById($temp_user,'admin','email');

if($post_data1['teacherName']===$repeatOr['values'][0]['realname']) {stop_exec('评价对象不能设置自己，请检查参数','prepare_setting');}

if(!isset($_POST['total']) or empty($_POST['total'])) {stop_exec('请输入班级的参考总人数！','prepare_setting');}

$total_num = $_POST['total'];


//这里添加设计一个数据库的方法，用于防止班主任们重复提交评价。
$estimate_repeat = $db_res->checkTheEstimateRepeat($teacherName,$className);
if($estimate_repeat) {stop_exec('出现重复的评价，请点击查看你当前的是否已经设置了相同的评价，点击导航栏“管理管理评价”即可','prepare_setting');}

$iPort = (int)$port +6;
for($i=$port;$i<=$iPort;$i++){
    $res = $sysCmd->checkIsSettingPort($i);
    $port = $i;
    if(!$res) break;
}

$fileContent = $sysCmd->createNodeWwwConfig($portTypeDetailId,$port);
$file = path()."TM2015".DIRECTORY_SEPARATOR."bin".DIRECTORY_SEPARATOR."www-$port";
file_put_contents($file,$fileContent);
$sysCmd->run_schtasks($port);
sleep(2);


$output = setting_estimate($port,$post_data);
$output1 = htmlspecialchars_decode($output);
if(!isset($output1))
{$output1="";}

$output2 = "[".$output1."]";
$backInfo = json_decode($output2);


if(isset($backInfo[0]->data->className))
{
echo "班级的名字是：".$backInfo[0]->data->className;
echo "讲师的名字是：".$backInfo[0]->data->teacherName;
$class_info = $backInfo[0]->data->classInfoId;

echo  "<script>alert('恭喜，目前还没有被评分的对象，你的设置有效');window.location.href='manageEstimating.php';</script>";

$creator = $_SESSION['xz_uid'];
sleep(1);
$addTime= date('Y-m-d H:i:s',time());
$expired_time = date('Y-m-d H:i:s',time()+60*60*1.5-70);
$dbport = estimate_type_port($port);
$db = gradeConn($dbport);
$db->query("update classInfo set creator ='$creator',typeDetail='$portTypeDetailId' where id='$class_info'");


    $list['sid'] = $subject;
    $list['classInfoId'] = $class_info;
    $list['who'] = $creator;
    $list['port'] = $port;
    $list['expired_time'] = $expired_time;
    $list['setting_time'] = $addTime;
    $list['classRoomName'] = $place;
    $list['teacherName'] = $teacherName;
    $list['className'] = $className;
    $list['total'] = $total_num;
    $list['typeDetail'] = $portTypeDetailId;
    $sql = insert($list,'estimate');
    $db_res->query_num($sql);

    $type_port = estimate_type_port($port);


    //注释下面这个语句，因为想转移别的平台了！！！
    /*refresh_status_create_schtasks($port);*/
    $sysCmd->estimating_html('xx',$type_port);
    //estimating_html('xx',$type_port);
    $sysCmd->FW_add($port,$place);

//1.先识别端口，然后执行生成一个评价防火墙的规则，并且增加到防火墙中
//2.然后生成一个计划任务，到特点时间就执行estimate_stop.php文件。


}

$infoLen = strlen($output1);

if ($infoLen<=0) {
    stop_exec("服务端无响应！可能已经断开通讯！！",'prepare_setting');
}
 ?>