<?php

include 'head-nav.php';
include 'function/function.php';
include 'function/feedback.php';
include 'view/tableView.php';
#date_default_timezone_set('Asia/Shanghai');



$list['settingTime'] = $date = time();
$list1['setting_time'] = date('Y-m-d H:i:s',time());
$list1['expired_time']  = date("Y-m-d H:i:s",time()+(60*60*2));

if(empty($_POST['feedback_option'])){


    $area = $db_res->getValuesById(SLocation,'teachingarea','tid');
    $areaArray = optionValues($area['values'],'block','--请选择--');

    $title='';
    $title[] = "请选择具体区域";
    $title[] = "请选择具体课室";
    $title[] = "请输入班级";
    $title[] = "你喜欢";

    $content[] = optionTableAddContent('block',$areaArray,'',' onchange=" getArea(this.value,\'place\',\'block\') " ');
    $content[] = optionTableAddContent('place','','',' id="place" ');
    $content[] = commonTableAddContent('input','text','className','','例如：0520UI三期基础班');
    $content[] = "<input type='submit' value='点击提交'  class='btn btn-success' onclick=\"return confirm('确定提交评价申请?,提交后需要大概5-8秒钟时间开启评价')\">";

    $title1[] = "请输入你想设置具体的学习反馈标题 colspan='4'";

    $content1[] = commonTableAddContent("textarea","","feedback_option",""," colspan='4' 复制标题到这个地方，注意每个标题之间换行，一行一个学习反馈标题","width: 600px;height: 250px;","","</textarea>");


    $contentArray[] = $content;
    $contentArray[]  =$title1;
    $contentArray[] = $content1;
}
else{
    goto next;
}
?>

<form action="" method="post" >

    <br>
    <?php
    listTableNoSN($title,$contentArray);
    ?>
    <br>

</form>
<?php exit; ?>

<?php
next:



$optionInfo = explode("\n",$_POST['feedback_option']);

/*foreach($otptionInfo as $value){
    $feedbackOption = addslashes($value);

}*/


$optionInfoJson =  json_encode($optionInfo);


//下面这个是解析好来自外部传入来的学习反馈的标题，解析出json格式，然后放到指定位置让nodejs读取运行。
$content = feedback_template($optionInfo);


//检查班级信息，具体课室
if(empty($_POST['className']) or empty($_POST['place']) or empty($_POST['feedback_option']))
{
    stop_exec('设置请求提交失败，请检查参数','feedback');
}



//文件指定放置的位置
$path = path().DIRECTORY_SEPARATOR."TM2015".DIRECTORY_SEPARATOR."routes".DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."question_f.json";

//下面这个是将在post过来的学习反馈的标题json格式化之后放到服务器指定的位置，让nodejs读取。
file_put_contents($path,$content);


//设置启动学习反馈调查设置的一些检查步骤
//检查端口是否已经被设置。检查可用端口。

//端口检查，去数据库查询post过来的port端口信息，看看是否在mysql数据库里面存在。
//如果没有该端口信息就中断运行。

$port = "9";
$port_check = $db_res->getValuesById($port,'port_type','id');
if(!empty($port_check)) {
    $port = $port_check['values'][0]['port'];
}


//2017-05-23 需要特别说明一下，那就是，下面这个端口检测就有意思了，不能跟前面的老师评价流程一样。
//这里需要检测一下空的端口。因为nodejs一旦启动，模板就没办法修改了。

$iPort = (int)$port +6;
for($i=$port;$i<=$iPort;$i++){
    $res = $sysCmd->checkIsSettingPort($i);
    $port = $i;
    if(!$res) break;
}


//下面这个可以正对具体的pid来停止nodejs。
//echo "<br>";
//$pid = $sysCmd->find_port_pid($port);
//$sysCmd->kill_specify_nodeById($pid);


//再补充一下，上面可以先放置指定格式的学习反馈格式。然后停止指定端口，再重新开启就可以了。
//还是针对上面的端口问题，可以换一个角度、可以增加一个kill，发现
//当前可用的话，可以把他杀掉，然后在启动同样端口之前
//确保好 你或者我 把你想设置的 学习反馈的选项转换好json格式并且放到指定的位置上。然后再开启就可以了。




//设置好相应nodejs需要的参数，等下准备转换json格式。
//顺便收集信息，用于插入到mysql数据库中做实时记录。

$list['className'] = $teacherName =  $className = POST_values('className');
$list['`option`'] = $optionInfoJson;
$place = POST_values('place');

$post_data1['teacherName'] = addslashes($teacherName);
$post_data1['className'] =  addslashes($className);

$post_data = json_encode($post_data1);


//创建nodejs所需要的启动文件，其中大概路径是 TM2015/bin这个文件夹里面。
$fileContent = $sysCmd->createNodeWwwConfig("9",$port);
$file = path()."TM2015".DIRECTORY_SEPARATOR."bin".DIRECTORY_SEPARATOR."www-$port";
file_put_contents($file,$fileContent);
$sysCmd->run_schtasks($port);
sleep(2);

//好，这里就是启动设置老师评价的主进程了，就是用node这个控制台来启动老师评价了。
$output = setting_estimate($port,$post_data);
$output1 = htmlspecialchars_decode($output);
if(!isset($output1))
{$output1="";}
//转格式，转数组。
$output2 = "[".$output1."]";
$backInfo = json_decode($output2);


//这里是返回评价设置的班级，讲师名字等等，都是从node的响应的json里面转换数组获取的。
if(isset($backInfo[0]->data->className)) {
    echo "班级的名字是：" . $backInfo[0]->data->className;
    echo "讲师的名字是：" . $backInfo[0]->data->teacherName;
    $class_info = $backInfo[0]->data->classInfoId;
}
else{
    echo "设置失败，请联系网管！！";exit;
}
//收集信息，用于插入到mysql数据库中做实时记录。



$list1['who'] = $list['`who`'] = $who =   $_SESSION['xz_uid'];
$list1['port'] = $port;
$list['className'] = $list1['className'] = $className = POST_values('className');
$list1['classInfoId'] = $list['classInfoId'] = $class_info;
$place = POST_values('place');

//更新一下sqlite里面的记录。
sleep(1);
$dbport = estimate_type_port($port);
$db = gradeConn($dbport);
$db->query("update classinfo set creator='$who' where id='$class_info'");
echo "update classinfo set creator='$who' where id='$class_info'";

//等待上面启动node成功之后就返回classInfo信息。然后就可以插入数据库作为记录了。


//2017-6-1 对需要转义的sql语句进行转义。

$list['`option`'] = addslashes($list['`option`']);

$insertSql = insert($list,"feedback");
$insertSql1 = insert($list1,"estimate");





$res = $db_res->query_num($insertSql);
$res1 = $db_res->query_num($insertSql1);



//最后需要设置的应该是1、根据课室算出对应的端口，设置防火墙。2.更新最新的html文件。


$type_port = estimate_type_port($port);

$sysCmd->estimating_html('xx',$type_port);
$sysCmd->FW_add($port,$place);



echo  "<script>alert('恭喜，你的设置有效');window.location.href='manageEstimating.php';</script>";


?>





