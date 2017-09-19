<?php
include 'head-nav.php';
include 'function/function.php';
include 'function/feedback.php';
include 'view/tableView.php';

?>

<script type="text/javascript">

    $(document).ready(function(){
        $("#date").change(function(){
            var date = $(this).find("option:selected").text();
            location.href = "feedback_export.php?date="+date;
        });



        $("#classInfoId").change(function(){
            var classInfoId = $(this).val();
            var url = window.location.href;
            location.href = url+'&classInfoId='+classInfoId;
        });


    });

</script>

<?php


$infoNow = $db_res->getValuesByTable('feedback');
$infoPast = $db_res->getValuesByTable('feedback_history');

/*if(empty($infoNow) and empty($infoPast)){
    echo "没有数据";exit;
}*/


$port = "8061";
$who = $_SESSION['xz_uid'];

//这里是实例化一个数据库连接到sqlite，里面主要装的是学习反馈信息。
$db = gradeConn($port);

if(isset($_GET['date']) or !empty($_GET['date']) ){
    goto this1;
}

//根据用户的具体权限去搜索的学生反馈数据
$who = $_SESSION['xz_uid'];

if($who=='10'){
    $info = $db->query("select * from classinfo  order by inputTime DESC");
}
else{
    $info = $db->query("select * from classinfo where creator='$who' order by inputTime DESC");
}


while($row =$info->fetchArray()){

    $infoDetail[] = $row;
    $recordDateInfo[] = date('Y-m-d',$row['inputTime']/1000);

}

if(empty($infoDetail)){echo "没有数据";exit;}

//2017--5-31
//这里另外计算出重复值，因为出错了啊，~~我还以为是数据问题。
/*for()*/

//计算出数据库当中所有学习反馈的调查时间，然后取出重复，展示给用户。
$recordDate = array_unique($recordDateInfo);

//2017--5--31--alter
foreach($recordDate as $value){
    $recordDates[]['date'] = $value;
}


$recordDateArray = optionValues($recordDates,'date',"请选择");
echo optionTableAddContent('date',$recordDateArray,""," id=\"date\" ");


exit;
this1:

if(isset($_GET['classInfoId']) or !empty($_GET['classInfoId']) ){
    goto this2;
}

$date = strtotime($_GET['date']);
$date = $date*1000;
$date1 = (int)$date+24*60*60*1000;

$feedbackClassInfo = "" ;


$port = "8061";



//下面这个可以根据时间列出具体这段时间内设置了学习反馈的信息。
if($who=='10'){
    $feedbackClassInfoArray = $db->query("select * from classinfo where inputTime BETWEEN '$date' AND '$date1' order by inputTime DESC");
}
else{
    $feedbackClassInfoArray = $db->query("select * from classinfo where creator='$who' and inputTime BETWEEN '$date' AND '$date1' order by inputTime DESC");
}

while ($row1 = $feedbackClassInfoArray->fetchArray()){
    $feedbackClassInfo[] = $row1;
}


$classInfoArray = optionValues($feedbackClassInfo,'className','请选择班级');
echo optionTableAddContent('className',$classInfoArray,''," id=\"classInfoId\" ");

exit;

this2:

$classInfoid = $_GET['classInfoId'];



//去数据库中查询关于该classId的相应学习反馈标题数量，并且转换成数组。
$feedbackTitle = $db_res->query("select * from feedback where classInfoId='$classInfoid'");
$feedbackTitleInfo = $feedbackTitle['values'][0]['option'];


//2017-6-1删除下面这些语句
//$feedbackTitleInfo =  preg_replace("#u#","\u",$feedbackTitleInfo);

//2017-6-1增加--对下面这些语句进行转义
/*print_r($feedbackTitleInfo);
$feedbackTitleInfo = stripcslashes($feedbackTitleInfo);*/

$feedbackTitleArray = json_decode($feedbackTitleInfo);

$feedbackTitleNum = count($feedbackTitleArray);

//初始化反馈标题的数组，准备用来计算各个选项的比率。
//默认t1表示的是，一个标题有多少个ABCD选项，默认是4个。
$qs=array();
for($t=0;$t<$feedbackTitleNum;$t++){
    for($t1=0;$t1<4;$t1++){
        $qs[$t][$t1]="";
    }
}


$feedbackStudentInfo = $db->query("select * from Comment where classInfoId='$classInfoid'");

/*if(empty($feedbackStudentInfo)){
    stop_exec("没有数据!!",'feedback_export');
}*/




while($row2 = $feedbackStudentInfo->fetchArray(SQLITE3_ASSOC)){
    $feedbackStudentInfoArray[] = $row2;
}

//如果检测到没有数据的话，自动跳到上一级。
$url = $_SERVER['HTTP_REFERER'];
if(empty($feedbackStudentInfoArray)){
    /*stop_exec("没有数据!!",'feedback_export');*/
echo "<script>alert('没有数据');window.location.href='$url';</script>";exit;
}

//得出该班级的评价时间和班级名字
$classInfo1 = $db->query("select * from classinfo where id='$classInfoid'");
while($row3 = $classInfo1->fetchArray(SQLITE3_ASSOC)){
    $className = $row3['className'];
    $settingDate = date('Y-m-d H:i:s',$row3['inputTime']/1000);
}

echo "<p>班级名字:".$className."--------调查时间:".$settingDate."</p>";

//算出学生数量
$studentNum = count($feedbackStudentInfoArray);



//开始循环所有学生的学习反馈数据，用于统计
foreach($feedbackStudentInfoArray as $value ){

    $info1 = explode(",",$value['scores']);

    $i = 0;
    $ia = 0;
    foreach($info1 as $value1){

        $score = ((int)ord($value1)-65);

        $qs[$i][$score]++;

        //这里限定了
        if($ia==$feedbackTitleNum){break;}

    $i++;
        $ia++;

    }

}

//开始循环所有的反馈标题的每一个选项。
//这里可以统计比率的同时，也可以初始化表格的内容
$content='';
foreach($qs as $key=>$values){

    $content[] = $feedbackTitleArray[$key];

    foreach($values as $value1s){


        $rate = round(($value1s/$studentNum)*100);
        $content[] = $rate."%";

    }

    $contentArray[] = $content;
    $content='';

}

$title='';
$title[] = "学习目标";
$title[] = "A非常清楚";
$title[] = "B基本清楚";
$title[] = "C有点模糊";
$title[] = "D几乎不懂";

listTableNoSN($title,$contentArray);


echo "<p>学员的反馈意见</p>";

$title1[] = "姓名";
$title1[] = "意见和建议";

$contentArray='';
foreach($feedbackStudentInfoArray as $key=>$value){

$content='';
    $content[] = $value['name'];
    $content[] = $value['comment'];
$contentArray[] = $content;
}




listTable($title1,$contentArray);

echo "<p>下面为原始的学习反馈数据</p>";


$title='';
$title[] = "姓名";
foreach($feedbackTitleArray as $value){

    $title[] = $value;

}

$contentArray='';

foreach($feedbackStudentInfoArray as $value){


    $content='';
    $content[] = $value['name'];
    $scores = explode(",",$value['scores']);
    foreach($scores as $values){
        $content[] = $values;
    }
    $contentArray[] = $content;
}

listTable($title,$contentArray);

