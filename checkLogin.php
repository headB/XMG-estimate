<?php
header("content-type:text/html;charset=utf-8");
$remote_IP = $_SERVER['REMOTE_ADDR'];
$now = date('Y-m-d H:i:s',time());
session_start();

if(!isset($_POST['submit'])){
    header('location:index.php');
}
if(!isset($_SESSION['verification']) or !isset($_POST['codeImage']))
{
    echo "<script>alert('验证码出错！！');window.location.href='index.php'</script>";exit;

}
$codeImage =md5($_POST['codeImage']);
if($_SESSION['verification']!=$codeImage)
{
    echo "<script>alert('验证码不正确！！');window.location.href='index.php'</script>";exit;
}
include("conn.php");
$unsafe_username = $_POST['username'];
$password = md5($conn->real_escape_string($_POST['password']));
$username = $conn->real_escape_string($unsafe_username);


$sql = "select * from admin where username='$username' and password='$password'";
$res = $conn -> query($sql);
$userinfo = $res ->fetch_assoc();

if($userinfo){
    $_SESSION['xz_uid'] = addslashes($userinfo['id']);
    $_SESSION['xz_username'] = addslashes($userinfo['username']);
    $_SESSION['xz_department'] = addslashes($userinfo['department']);
    $_SESSION['xz_email'] = addslashes($userinfo['email']);
    $username = $_SESSION['xz_username'];
    echo "<script>alert('恭喜您，登录成功');window.location.href='prepare_setting.php'</script>";
    $re = $conn->query("update admin set last_login_time='$now',last_login_ip='$remote_IP' where username='$username'");

}else{
    echo "<script>alert('用户名或密码错误');window.location.href='login.php'</script>";exit;
}


?>