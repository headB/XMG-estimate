<?php

/*include 'exec_auth.php';*/
@header('content-type:text/html;charset=utf-8');

require_once 'function/function.php';
include_once 'conn.php';
@session_start();
if(isset($_SESSION['xz_uid'])){
$userId = $_SESSION['xz_uid'];}
date_default_timezone_set('Asia/Shanghai');
$time = date('Y-m-d H:i:s',time());
$time1 = date('H:i',time());
$cancelTime = date('Y-m-d H:i:s',time()-60);

//手动去停止评价
if(isset($_GET['classInfoId']) and $_GET['classInfoId']!="" and  isset($userId) and $userId!="")
{
	$classId = $_GET['classInfoId'];
	 $sysCmd->query_num("update estimate set expired_time ='$cancelTime' where classInfoId='$classId'");
}


//下面是系统自动设置计划任务激活去自动清除清除
/*if(!isset($_POST['classInfoId']) and !isset($_POST['userId']))*/
$resFetch1 = $sysCmd->query("select * from estimate where expired_time <= '$time'");
$resFetch2 = (!empty($resFetch1['values']))?$resFetch1['num']:'0';
echo $resFetch2;
if($resFetch2<=0)
{
    $sysCmd->KillAllFreeRunningEstimate();
    $sysCmd->estimating_html('xx');

    preg_match("#23:\d\d#",$time1,$matchTest);
    if(!empty($matchTest))
        {goto next; }

	stop_exec("执行完成！！","managerSql");
	
}
$res="";
foreach($resFetch1['values'] as $row)
{
    if(empty($row['teacherName']) or $row['teacherName']=='0'){
        goto this2;
    }
	$sql = insert($row,"estimate_history");
    $sysCmd->query_num($sql);
    this2:
    $classDelId = $row['classInfoId'];
      $type = $row['port'];

    $pid = $sysCmd->find_port_pid($type);
        $sysCmd->kill_specify_nodeById($pid);
    $sysCmd->query_num("delete from estimate where classInfoId ='$classDelId'");
   /* exec("schtasks /delete /TN stop_pingjia-$type /F",$message);
    print_r($message);
    echo $time;*/
    //这里还需要添加一个新的函数，根据上面给出所有的端口号去精准杀掉程序的pid
    //注意了，这里还需要在数据库里面搜索出所有的初始化端口，然后检测是否在特殊的评价对象，是的话，只需要清楚实时评价信息，否则的话就全部定制
    
}

    //这个位置是用来更新--实时所有的评价的----

    $sysCmd->KillAllFreeRunningEstimate();
    $sysCmd->estimating_html('xx');


/*print_r($res);*/
//1.执行关闭端口,使用函数estimate_stop();
//2.线识别端口类型，执行删除防火墙规则
//3.执行删除任务计划规则
next:
echo $time1;
preg_match("#23:\d\d#",$time1,$matchTest);
$sysCmd->timer_for_stop_estimate_all($matchTest);


 ?>