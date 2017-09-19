<?php
header("content-type:text/html;charset=utf-8");
include("conn.php");
include("function/function.php");
include 'function/usermanage.php';


if(isset($_POST['key_syn'])) {
    if ($_POST['key_syn'] == 'dsfzxcfgsdfdhsss#') {
        goto syn_user;
    }
}

session_start();
if(!isset($_SESSION['xz_username']))
{header('location:login.php');exit;}

syn_user:

echo "OKOKOKOK!!!";
$url1 = '192.168.113.2/form/user_insert.php';
$res = exec_operate_only_for_form_client($_POST,$conn,$url1);
echo $res;



?>