<?php
date_default_timezone_set('Asia/Shanghai');
$now = date("Y-m-d H:i:s",time());
$script = "<script>
function addressadd()
{document.use.submit();}
function addressadd()
{document.use1.submit();}
</script>";
include 'head-nav.php';
include 'conn.php';
include 'function/function.php';
$username = $_SESSION['xz_username'];
$remote_IP = $_SERVER['REMOTE_ADDR'];
$re = $conn->query("update admin set last_login_time='$now',last_login_ip='$remote_IP' where username='$username'");

echo "<script language=\"JavaScript\"> self.location='prepare_setting.php';</script>";

/*exec("tcp.exe -w 0.2 -n 1 127.0.0.1 81",$res1);
if(!isset($res[7]))
{
	run_schtasks("81");
	run_schtasks("91");d
	run_schtasks("71");
}*/

?>