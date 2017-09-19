<?php 
header("content-type:text/html;charset=utf-8");
include 'head-nav.php';
include ('conn.php');
if(!isset($_POST['class']))
{
    header('location:1.php');exit;
}
$class = $_POST['class'];
$sql = " select distinct createDate from itcaststudent where className = '$class' order by createDate DESC";
$res = $conn1->query($sql);
$list = array();
while($row = $res->fetch_assoc())
{ $list[] = $row;}
?>


<center>学习情况调查表--数据导出引导页面<br>
当前选择的班级----<?php echo $class; ?><br><br>
<div style="width:460px;border:1px solid black;">
<form action="3.php" method="post" id="all">
<input type="hidden" name="className" value="<?php echo $class; ?>" >
<select name="date" style="font-size:30px">

<?php foreach($list as $v)

{	echo "<option>".$v['createDate']."</option>";
}

?>

</select>

<br>
<input type="submit" value="点击导出EXCEL表" style="font-size:28px">
</div>
</form>
<br>
<br>

请选择网页在线版的调查表调查时间
<div style="width:460px;border:1px solid black;">
<form action="4.php" method="post" id="all">
<input type="hidden" name="className" value="<?php echo $class; ?>" >
<select name="date" style="font-size:30px">


<?php foreach($list as $v)

{	echo "<option>".$v['createDate']."</option>";
}

?>

</select>

<br>
<input type="submit" value="点击在线查看" style="font-size:28px">
</div>
</form>

</center> 
</body>
</html>