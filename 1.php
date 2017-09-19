<?php header("content-type:text/html;charset=utf-8");
include ('conn.php');
include 'head-nav.php';
$sql = "select * from classinfo";
$res = $conn1->query($sql);
$list = array();
while($row = $res->fetch_assoc())
{$list[]= $row;}


?>




<center>学习情况调查表--数据导出引导页面<br><br>
学习情况调查数据导出----请先选择班级<br><br>
<form id="all" method="post" action="2.php"> 
<select name="class" style="font-size:30px;" >
<?php 

     
	 foreach($list as $v)
	 {
	 echo "<option>".$v['name']."</option>"."<br>";
	 }

?>
</select>
<input type="submit" value="确定" style="font-size:30px;">

</form>
</center> 
</body>
</html>