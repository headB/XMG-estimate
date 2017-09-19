<?php
include 'head-nav.php';
include 'conn.php';

if(!isset($_POST['class']) or !isset($_POST['classTeacher']) or !isset($_POST['graduation']) )
{
    exit;
}
    $class = $_POST['class'];
    $whoCreate = $_POST['classTeacher'];
    $graduation = $_POST['graduation'];
$date = date("Y-m-d");

if($class=='' || $whoCreate=='' || $graduation==''||$date == '')
{echo "<script>alert('新建班级失败，可能原因：缺少必要的信息，请检查！');
window.location.href='banzhuren.php'</script>";exit;}

$res = $conn1->query("insert into classinfo(name,recoroderStatus,whoCreate,graduation,createDate)
 values('$class','RECORDERING','$whoCreate','$graduation','$date')");

if($res)
{
    echo "<script>alert('班级创建成功!!')</script><br>"."你的班级→→→".$class."→→→已经创建成功！！";
    echo "<a href='index.php'>点击返回首页</a>";
}
    else
    	{echo "<script>alert('班级创建失败！！可能已经存在班级，请勿重复创建')</script>";
            echo "<a href='index.php'>点击返回首页</a>";
        }

 ?>
</center>
</body></html>