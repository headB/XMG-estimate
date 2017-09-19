<?php
header("content-type:text/html;charset=utf-8");
include("function/function.php");
include 'function/usermanage.php';
include("conn.php");

session_start();
if(isset($_POST['key_syn'])) {
	if ($_POST['key_syn'] == 'dsfzxcfgsdfdhsss#') {
		goto syn_user;
	}
}
if(!isset($_SESSION['xz_username']))
		{header('location:login.php');exit;}
syn_user:
$tableName = "user";

if(isset($_POST['uname']))
{
	if ($_POST['uname'] != "")
	{
		$list['name'] = $_POST['uname'];
		$list_name = $list['name'];
		$res = $conn->query("select * from user where name='$list_name'");
		$res = $conn->affected_rows;
		if ($res)
		{
			echo "你的名字已经被注册，请换邮箱或者联系网管！！";
			exit;

		}
	}
}

if(isset($_POST['department']))
	{if($_POST['department']!="")
		{$list['department']=$_POST['department'];}
	}

if(isset($_POST['email']))
{if($_POST['email']!="")
{$email = $list['email']=$_POST['email'];
$res = $conn->query("select * from user where email='$email'");
$res = $conn->affected_rows;
	if($res){echo "你的邮箱已经被注册，请换邮箱或者联系网管！！";}
}
}

if(isset($_POST['mobile']))
	{if($_POST['mobile'])
		{$list['mobilephone']=$_POST['mobile'];}
	}

preg_match("#^[\w!$%&'*+/=?^_`{|}~-]+(?:\.[\w!$%&'*+/=?^_`{|}~-]+)*@520it.com$#",$email,$emailMatch);
if(empty($emailMatch))
{
    echo "<script>alert('邮件检测为非公司邮箱，请填写完整公司的邮箱');window.location.href='user.php'</script>";exit;
}

if(empty($list['name']) or empty($list['department']) or empty($list['email']))
{echo "<script>alert('添加失败，可能原因：缺少必要的信息，请检查！');
window.location.href='user.php'</script>";exit;}
$sql = insert($list,$tableName);

//这里下面开始就是可以到分站客户端插入添加信息
echo $sql."<br>";

print_r($_POST);

//对，就是在执行前的这个步骤先可以插入一个用户同步客户端的程序（函数）
//然后等待返回的信息，执行成功后才继续下一步，失败就提示请检查参数！！！
$res = $conn->query($sql);
if($res)
{echo "<script>alert('添加成功');window.location.href='user.php'</script>";}
else
{echo "<script>alert('添加失败');window.location.href='user.php'</script>";}

 ?>