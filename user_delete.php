<?php
session_start();
if(isset($_POST['key_syn'])) {


	if ($_POST['key_syn'] == 'dsfzxcfgsdfdhsss#') {
		goto syn_user;
	}
}
if(!isset($_SESSION['xz_username']))
		{header('location:login.php');exit;}
syn_user:
header("content-type:text/html;charset=utf-8");
include("function/function.php");
include("conn.php");
$tableName = "user";
$tableName1 = "assets";



if(isset($_POST['email']))
{
	if($_POST['email'] != "" )
	{$email = $where['email']=$_POST['email'];}
}


$sql = delete($tableName,$where);

$res = $conn->query("select * from admin where email='$email'");
$res = $conn->affected_rows;
if($res=='1')
{
	$res = $conn->query("delete from admin where email='$email'");
	$res = $conn->affected_rows;
}



$res = $conn->query("select * from user where email='$email'");
$res = $conn->affected_rows;


if($res=='1')
{
	$res = $conn->query($sql);
	$res = $conn->affected_rows;
	if($res=='1') {

		echo "<script>alert('X栋删除用户成功！！')</script>";exit;

	}


}
else
{
	$sql = "select * from user where email='$email'";
	$res = $conn->query($sql);
	$res = $conn->affected_rows;
	if($res=='1')
	{
		echo "存在的用户，并且删除用户失败！！";
	}
	else
		{
			echo "不存在的用户，删除用户成功！！";
		}



}



 ?>