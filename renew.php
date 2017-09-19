<?php
include 'head-nav.php';

$userName = (isset($_POST['username']) and !empty($_POST['username']))?$_POST['username']:"";
$passwd = (isset($_POST['passwd']) and !empty($_POST['passwd']))?$_POST['passwd']:"";

define('username',$userName);
define('password',$passwd);
define('tcp_ping','126.0.0.1');

include 'function/function.php';

if($systemType=='linux'){
    stop_exec('检测到当前系统为linux，无需初始化程序，只需要设置配置core文件夹里面的globalConfig.php即可');
}
//这里设计一个用于检测当前系统是不是属于linux的系统，如果是的话，就终止程序，直接跳转到首页就可以了，linux并不需要删除更新文件，因为是直接启动的

include 'conn.php';
include 'view/tableView.php';
if(empty($userName) or empty($passwd)){
    goto next;
}
test_passwd(username,password,'renew');
/*if(!empty($userName) and !empty($passwd)){
    test_passwd($userName,$passwd);
}*/

echo "<br>--".username;
echo "<br>--".password;


$sysCmd->renew_bat_file();


$setUpPortArray = $db_res->getValuesById('0','port_type','tid');
if(!empty($setUpPortArray)){
    foreach($setUpPortArray['values'] as $value)
    {
        $endPort = (int)$value['port']+8;
        for($i=$value['port'];$i<=$endPort;$i++)
        {
            $sysCmd->renew_bat_schtask($i);
        }
    }
}
else{
    echo "check the database where if exist the information for the table estimate";exit;
}


//上面把BAT脚本清除好了以后，就进行下面的kill node 所有程序活动
$sysCmd->stop_estimate_all(username,password);

//后面之所以加上这个文件，主要是用于更新当前评价状态

//注意啦，上面的操作可以照常运行，就是到了这里一步，抢先一步啦，这里是新建所有的schtasks任务程序啊，事件啦。！
//创建所有的端口schtasks任务，以后就不用创建了，只需要一次初始化就搞掂了。
$setUpPortArray = $db_res->getValuesById('0','port_type','tid');
if(!empty($setUpPortArray)){
    foreach($setUpPortArray['values'] as $value)
    {
        $sysCmd->setUpAllPortSchtasks($value['port']);
    }
}
else{
    echo "check the database where if exist the information for the table estimate";exit;
}


//创建好以后再把所有的程序杀了
/*stop_estimate_all(username,password);*/

include 'estimate_stop.php';
exit;
next:
$title="";
$title[] = '用户名';
$title[] = '密码';
$content = "";
$content[] = commonTableAddContent('input','text','username',"","请输入本计算机的用户名");
$content[] = commonTableAddContent('input','text','passwd',"","请输入对应用户名的密码");
$contentArray[] = $content;
?>

<br><br>
<p>运行本初始化脚本前，请你阅读以下详细的说明</p>
<p>1.运行该脚本请确保没有其他重要的评价设置正在运行</p>
<p>2.有些详细的配置信息需要在本脚本所有的文件夹，请务必找到core/globalConfig.php,填写相关正确的核心信息。</p>
<p>3.确认好上面两步以后，你就可以输入该服务器的正确账号密码去初始化/重置评价程序的设置</p>

<form action="" method="post" onsubmit="return confirm('若账号密码正确验证后，需要30秒左右初始化/恢复配置，请不要刷新，谢谢！')">
    <?php
    listTableNoSN($title,$contentArray);

    ?>
<input type="submit" class="btn btn-success"  value="点击确认">
</form>


</center>
</body>
</html>