<?php
header("content-type:text/html;charset=utf-8");
include("conn.php");
include("function/function.php");
session_start();

if(isset($_POST['key_syn'])) {
	if ($_POST['key_syn'] == 'dsfzxcfgsdfdhsss#') {
		goto syn_user;
	}
}

if(!isset($_SESSION['xz_username']))
		{header('location:login.php');exit;}

syn_user:
$tableName1 = "assets";
$tableName2 = "user";

if(isset($_POST['department']))
	{if($_POST['department'])
		{$list1['department']=$list['department']=$_POST['department'];}
	}

if(isset($_POST['mobile']))
	{if($_POST['mobile'])
		{$list1['mobilephone']=$_POST['mobile'];}
	}

if(isset($_POST['email']))
{if($_POST['email'])
{$email = $list1['email']=$_POST['email'];}
}

preg_match("#^[\w!$%&'*+/=?^_`{|}~-]+(?:\.[\w!$%&'*+/=?^_`{|}~-]+)*@520it.com$#",$email,$emailMatch);
if(empty($emailMatch))
{
    echo "<script>alert('邮件检测为非公司邮箱，请填写公司的邮箱');window.location.href='user.php'</script>";exit;
}

if(isset($_POST['id']))
	{if($_POST['id'])
		{$where2['id']=$where1['user']=$_POST['id'];}
	}
print_r($_POST);
if(isset($_POST['key_syn']))
{
	if ($_POST['key_syn'] == 'dsfzxcfgsdfdhsss#') {
		$name = $_POST['uname'];
		$email1 = $_POST['email'];
		$res = $conn->query("select * from user where name='$name' ");
		$num = $res->num_rows;
		echo "----".$num."---";
		$res = $res->fetch_assoc();
		$res = $res['id'];

		$where2['id'] = $where1['user'] = $res;
		echo $res;

	}
}



$sql1 = update($list1,$tableName2,$where2);
$res1 = $conn->query($sql1);

		$res1 = $conn->affected_rows;
		

		if(isset($res1))
		{
			if ($res1 == "1")
			{
				echo "<script>alert('成功更新用户信息');window.location.href='user.php'</script>";
			} else
			{
				echo $name;
				echo $department = $list1['department'];
				echo $mobilephone = $list1['mobilephone'];
				echo $email = $list1['email'];
				$res = $conn->query("select * from user where name1='$name' and department='$department' and email='$email' and mobilephone='$mobilephone'");
				$res_num = $conn->affected_rows;
				if($res_num)
				{
					echo "<script>alert('内容没变化！成功更新用户信息');window.location.href='user.php'</script>";
				}
				else
				{
				echo "<script>alert('更新用户信息失败,或者是前后信息没有变化');window.location.href='user.php'</script>";
				}
			}
		}
else
{echo  "<script>alert('更新用户信息失败');window.location.href='user.php'</script>";}
	/*}
	else
		{echo "<script>alert('更新用户信息失败!');window.location.href='user.php'</script>";}*/

 ?>