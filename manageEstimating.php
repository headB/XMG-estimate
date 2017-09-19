<?php
date_default_timezone_set('Asia/Shanghai');
include 'head-nav.php';
include 'conn.php';
include 'function/function.php';
$time1 = "2015-12-2 16:00:00";
$time = date("Y-m-d H:i:s",time());
 ?>

 下面列出的是你当前所有的还在进行中的评价项目
 <br>
 <table class="table table-responsive table-striped table-hover table-bordered" style="width:auto" cellspacing="0" cellpadding="0" border="1" >
 	
 	<tbody>
 		
 		<tr>
 			<td style="text-align:center">序号</td>
 			<td style="text-align:center">评价类别</td>
			<td style="text-align:center">班级</td>
			<td style="text-align:center">被评价人</td>
			<td style="text-align:center">评价有效时间到</td>
			<td style="text-align:center" colspan="2">操作</td>
			<td style="text-align:center">点击查看评价人数</td>
 			
 		</tr><tr>
 		<?php 
	
	$creator = $_SESSION['xz_uid'];
$resFetch = $conn->query("select * from estimate where who='$creator' and expired_time>='$time' order by setting_time DESC ");

if($resFetch->num_rows===0){echo "<p style='color:red'>OMG,什么都没有诶！</p>";exit;}
$i = 1;
        //下面是循环数据表estimate里面的每一条记录，然后根据时间来决定他们的下一步操作。
        //超过expire_time的数据记录将会被转移到另外一个历史记录表。
while($row = $resFetch->fetch_assoc())
	{

		$res[] = $row;
		$v3=$row['classInfoId'];
		$estimateType = $row['port'];
		$estimateTypeDetailId = $row['typeDetail'];
		$res1 = $sysCmd->is_port_exist($estimateType);

		//这里判断的是有没有ping通端口
		if($res1) //这里是ping的通的前提下，然后判断值classInfoID是否一样
			{

			$output_test3 = detect_isset_estimate($estimateType);
                //有设置对象的，查看历史和当前对象时候一致。里面应该有两个判断流程。！
					if (isset($output_test3[0]->data->className))
							{$cNameId = $output_test3[0]->data->classInfoId;
							//对比结果！！对比data->classInfoId;
							if($v3==$cNameId)
								{
									$estimateType1 = estimate_type_port($estimateType);
									$estimateType = estimate_type($estimateType);
									$estimateTypeDetail = $db_res->getTypeNameById($estimateTypeDetailId,'port_type');
									@$estimateTypeDetail = $estimateTypeDetail['type'];
									$v1=$row['className'];
									$v2=$row['teacherName'];
									$v3=$row['classInfoId'];
									$v5=$row['expired_time'];
									$v4=$row['setting_time'];
echo<<<table
				<tr>
			<td style="text-align:center">$i</td>
			<td style="text-align:center">$estimateTypeDetail</td>
			<td style="text-align:center">$v1</td>
			<td style="text-align:center">$v2</td>
			<td style="text-align:center">$v5</td>
			<td style="text-align:center"><a target="_blank" href="managerSqlEx.php?id=$v3&port=$estimateType1&name=$v2&class=$v1&time=$v4&typeDetailId=$estimateTypeDetailId">导出结果</a></td>
			<td style="text-align:center"><a onclick="return confirm('确定停止评价？---$v2--$v1---！！！')" href="estimate_stop.php?classInfoId=$v3">停止评价</a></td>
			<td style="text-align:center"><a target="_self" href="viewRes.php?classId=$v3&port=$estimateType1">查看this</a></td>
			</tr>
table;
								}
							if($v3!=$cNameId)
								{   
									$sql = insert($row,"estimate_history");
  									$conn->query("$sql");
      								$type = $row['port'];
    								$conn->query("delete from estimate where classInfoId ='$v3'");

								}
							}
					if (!isset($output_test3[0]->data->className))
						{
							$output_test3 = "";
							$sql = insert($row,"estimate_history");
							$v3=$row['classInfoId'];
							$conn->query("$sql");
      						$type = $row['port'];
     						//estimate_stop($type);
    						//$conn->query("delete from estimate where classInfoId ='$v3'");
							//estimate_stop($estimateType);
						}
			}

		if(!$res1)//这里的是ping不同的情况
		{

			$sql = insert($row,"estimate_history");
			$v3=$row['classInfoId'];
			$conn->query("$sql");
   			$classDelId = $row['classInfoId'];
      		$type = $row['port'];
     		//estimate_stop($type);
    		//$conn->query("delete from estimate where classInfoId ='$v3'");

							}


$i++;}



	 ?>

</tr>



 	</tbody>


 </table>



</center>
</body>
</html>