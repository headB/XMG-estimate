<?php
##微信自动推送消息。先去设计数据库先
include 'head-nav.php';
include 'function/function.php';
include 'view/tableView.php';

print_r($_SESSION);

$POST = filter_postdata1($_POST);


if(!empty($_GET['req']) and $_GET['req']=='del' and !empty($_GET['ID']) ){

    $ID = $_GET['ID'];
    goto next;
}

if(!empty($POST['content']) and !empty($POST['sendTime'])){

    $list['content'] = $POST['content'];
    $list['sendTime'] = $POST['sendTime'];

    $sql = insert($list,'timer_send');
    $res = $db_res->query_num($sql);

    if($res){
        echo  "<script>alert('恭喜，设置成功!!');window.location.href='autoPush.php';</script>";
    }

    else{
        echo  "<script>alert('插入失败！！');window.location.href='autoPush.php';</script>";
    }

}


$title = '';

echo "添加发送的内容";

$title[] = '内容';
$title[] = '发送时间';
$title[] = '操作';

$content[] = commonTableAddContent('input','text','content','','请输入中文');
$content[] = commonTableAddContent('input','time','sendTime','','');
$content[] = "<input type='submit' value='点击提交'  class='btn btn-success' onclick=\"return confirm('确定提交评价申请?')\">";

$contentArray[]  = $content;


echo "<form action=\"\" method=\"post\" >";
listTableNoSN($title,$contentArray);
echo "</form><br>";

$title = '';
$content='';
$contentArray='';


$title[] = '内容';
$title[] = '发送时间';
$title[] = "操作 colspan='2' ";

#$contentInfoArray = $db_res->getValuesByTable('timer_send');
$contentInfoArray = $db_res->query("select * from timer_send order by sendTime");



foreach($contentInfoArray['values'] as $value){

    $ID = $value['ID'];
    $content[] = $value['content'];
    $content[] = $value['sendTime'];
    $content[]=  $value['ID']."编辑";
    $content[]=  "<a href='autoPush.php?req=del&ID=".$value['ID']."'>删除</a>";

    $contentArray[] = $content;
    $content='';
}

    listTable($title,$contentArray);

#print_r($contentArray);

exit();
next:

$sql = "delete from timer_send where ID='$ID'";
$res = $db_res->query_num($sql);


if($res){
    echo  "<script>alert('删除成功！！');window.location.href='autoPush.php';</script>";
}

else{
    echo  "<script>alert('删除失败！！');window.location.href='autoPush.php';</script>";
}
