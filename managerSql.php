<?php 

include('head-nav.php');
include('function/function.php');
include 'conn.php';

?>

<script language="JavaScript" type="text/javascript">


	var InterValObj; //timer变量，控制时间
	var count = 20; //间隔函数，1秒执行
	var curCount;//当前剩余秒数
	var i = 1;

	function sendMessage() {

		var x=document.getElementById("loading");
		curCount = count;
		InterValObj = window.setInterval(SetRemainTime, 25); //启动计时器，1秒执行一次

	}


	//timer处理函数
	function SetRemainTime() {
		if (curCount == 0) {
			window.clearInterval(InterValObj);//停止计时器
			i = 0;
		}
		else {
			var i1 = i*10;
			i++;
			curCount--;
			$("#loading").css("width",i1+"%");
		}
	}

	$(document).ready(function(){

	$(".target").hide();

		//$('#bn100').click(function(){ $('#bn200').attr(disabled='disabled');});
		$("[type='submit']").click(function(){
			$(".target").show();
			sendMessage();
		});
	});
</script>

<?php
$sysCmd->run_port_for_db();

date_default_timezone_set('Asia/Shanghai');
$time = date("Y-m-d H:i:s");
/*$time = "2015-12-01 18:00:00";*/

if(isset($_GET['port']) and $_GET['port']!="")
{
	
	$estimate_type = $_GET['port'];

$CES_res1 = $conn->query("select port from port_type where type='$estimate_type'");
$portArray = $CES_res1->fetch_row();

$port = $portArray['0'];

$port = estimate_type_port($port);

$db = gradeConn($port);

}
else
{
	$CES_port_type = $db_res->getValuesById('0','port_type','tid');
 echo "<p>请先选择需要导出评分的对象</p>";
 echo "<form name='use' action='managerSql.php' method='GET'>";


foreach ($CES_port_type['values'] as $value) {
	$CES_type = $value['type']; 
	$CES_type_port = $value['port'];
echo<<<select_type

	
	<input type="submit" style="font-size:25px" name="port" value="$CES_type" onclick="addressadd()">


	
select_type;
}
echo "</form>";

	echo "<br><br>
    <div  class=\"progress target\" style=\"width:50%;height: 80px\">
        <div  class=\"progress-bar target\" role=\"progressbar\" aria-valuenow=\"60\"
             aria-valuemin=\"0\" aria-valuemax=\"100\" id=\"loading\" style=\"width: 0;\">
        </div>
    </div>";

exit;
}



if(isset($_GET['id']) and $_GET['id']!="")
{	


$output_test3 = detect_isset_estimate($port);
if (isset($output_test3[0]->data->className)) {
    $cName = $output_test3[0]->data->className;$tName = $output_test3[0]->data->teacherName;
    echo<<<html
    <script>alert("目前已经有在评的对象：$cName--$tName",无法删除，请停止评价后再操作！);window.location.href="managerSql.php";</script>

html;
    exit;
}

	$id = $_GET['id'];



$resFetch = $conn->query("select * from estimate where classInfoId = '$id' and  expired_time >= '$time'");

$num = $resFetch->num_rows;

if($num===1)
{
	echo<<<html
	<script>alert("目前已经有在评的对象:,无法删除，请停止评价后再操作！");window.location.href="managerSql.php";</script>

html;
exit;
}

	$xz_uid = $_SESSION['xz_uid'];
	if($_SESSION['xz_department']=='18')
	{
		$db->query("delete from comment where classInfoId='$id'");
		$db->query("delete from classinfo where id='$id'");
	}

}
if($_SESSION['xz_department']=='23' or $_SESSION['xz_department']=='18')
{
	$res = $db->query("select * from classinfo  order by inputTime DESC");
}
else
{
	$creator = $_SESSION['xz_uid'];
	$res = $db->query("select * from classinfo where creator='$creator' order by inputTime DESC");
}

while($row =$res->fetchArray())
	{$info[] =$row; }
 ?>
<p>当前选择的评分对象是<?php echo $estimate_type ?></p>
	<table class="table table-responsive table-striped table-hover table-bordered" style="width:auto" cellspacing="0" cellpadding="0" border="1" >
		<tbody>
			<tr>
			<td style="text-align:center">序号</td>
			<td style="text-align:center">老师名字</td>
			<td style="text-align:center">班级</td>
			<td style="text-align:center">开启评价时间</td>
			<td style="text-align:center" colspan="2">操作</td>
			<td style="text-align:center">点击查看评价人数</td>
			</tr>

<?php

if(isset($info)){
$i=1; foreach ($info as $v) {
		$typeDetailId = $v['typeDetail'];
	$v1=$v['className'];
	$v2=$v['teacherName'];
	$v3=$v['id'];
	$v4=date("Y-m-d H:i",$v['inputTime']/1000);
	$v5=date("Y-m-d Hi",$v['inputTime']/1000);

echo<<<table
				<tr>
			<td style="text-align:center">$i</td>
			<td style="text-align:center">$v2</td>
			<td style="text-align:center">$v1</td>
			<td style="text-align:center">$v4</td>
			<td style="text-align:center"><a target="_blank" href="managerSqlEx.php?id=$v3&name=$v2&port=$port&class=$v1&time=$v5&typeDetailId=$typeDetailId">导出结果</a></td>
			<td style="text-align:center"><a onclick="return confirm('确定删除？请确定好当前的评价---$v2--$v1---已经停止评价！！！')" href="managerSql.php?id=$v3&port=$estimate_type">删除</a></td>
			<td style="text-align:center"><a target="_self" href="viewRes.php?classId=$v3&port=$port&type=$estimate_type">查看this</a></td>
			</tr>

table;
$i++;}
}
?>
		</tbody>



	</table>

	
	</center>
</body>
</html>