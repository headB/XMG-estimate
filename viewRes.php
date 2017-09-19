<?php
include('function/function.php');
include('head-nav.php');
if(isset($_GET['classId']) and $_GET['classId']!="")
{$id=$_GET['classId'];
$port=$_GET['port'];

if(isset($_GET['type']))
{
$port1 = $_GET['type'];
}
$db = gradeConn($port);
 }
else
{exit;}

$res = $db->query("select * from comment where classInfoId='$id'");
$i=0;
while($row = $res->fetchArray())
{$info[]= $row;$i++;}

if(!isset($_GET['type']))
{

if($i<=0)
	{echo "<script>alert('暂时没有人评价');window.location.href='manageEstimating.php';</script>";}
else
	{
echo<<<html
	 <script>alert("目前被评价获取到的评价人数为$i");window.location.href="manageEstimating.php";</script>

html;

}
exit;
}

if($i<=0)
	{echo "<script>alert('暂时没有人评价');window.location.href='managerSql.php?port=$port1';</script>";}
else
	{
echo<<<html
	 <script>alert("目前被评价获取到的评价人数为$i");window.location.href="managerSql.php?port=$port1";</script>

html;
}
 ?>
</center>
</body>
</html>