<?php
include('conn.php');
include 'head-nav.php';
include 'function/function.php';
$class = $_POST['className'];
$date = $_POST['date'];
$sql = "select * from itcaststudent where className = '$class' and createDate = '$date'order by learningContent";
$res = $conn1->query($sql);
$list = array();
while($row = $res->fetch_assoc())
	{$list[] = $row; }

 ?>

	<p></p>
	<p></p>
	<center><p>当前查看的班级→→→→→<?php echo $class; ?>当先查看调查表内容的时间→→→→→→<?php echo $date ; ?></p></center>
	<p></p>
	<p></p>
	<table width="auto" align="center" cellpadding="0" cellspacing="0" border="1" style="border:1px solid black" >
		<tbody>
			<tr >
				
			    <td  style="text-align:center">序号</td>
				<td style="text-align:center">姓名</td>
				<td style="text-align:center">班级</td>
				<td style="text-align:center">你今天所学的内容</td>
				<td style="text-align:center">你今天吸收了多少</td>
					
				<td style="text-align:center">今天所学不明白的地方</td>
				<td style="text-align:center">记录的IP地址</td>
					
			</tr>

			<?php $i=1; foreach($list as $v){;?>
			<tr>
				<td style="text-align:center"><?php echo $i; ?></td>
				<td style="text-align:center"><?php echo $v['studentName']; ?></td>
				<td style="text-align:center"><?php echo $v['className']; ?></td>
				<td style="text-align:center"><?php echo $v['learningContent']; ?></td>
				<td style="text-align:center"><?php echo $v['receivePercent']; ?></td>
				<td width="300" style="text-align:center"><?php echo  post_safe_html($v['unkownNote']); ?></td>
				<td style="text-align:center"><?php echo $v['ipaddress']; ?></td>
			
				
			</tr>
			<?php $i++; }?>

		</tbody>

	</table>
</center>
</body>
</html>