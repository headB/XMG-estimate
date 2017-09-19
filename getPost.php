<?php
include 'conn.php';
include 'core/globalConfig.php';
include 'random.php';
include 'sendCodeEmail.php';
header('content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
$timeExpired = date("Y-m-d H:i:s",time()+60*30);

if(isset($_POST['reregistCodeSyn'])) {
    if ($_POST['reregistCodeSyn'] != "CCCXxsaddddfgte#") {
        echo "错误！！！";
        exit;
    }
    else
    {
        goto this1;
    }
}






if(isset($_POST['key_syn']))
{
    if($_POST['key_syn']=='ttyderfre4e345wer345wer34ee')
    {

    }
    else
    {
        echo "error";exit;
    }
}

if(isset($_POST['maskCode']) and ($_POST['maskCode'] =='asd123!@#zcdaCCdDDDLp')){

    goto this2;
}

this1:

if(!isset($_POST['emailName']) or $_POST['emailName']=="")
{
    echo "请输入你的名字";exit;
}
$emailName1 = $_POST['emailName'];

if(!isset($_POST['randomNum']) or $_POST['randomNum']=="")
{
    echo "请输入你的名字";exit;
}
$randomNum = $_POST['randomNum'];





if(!isset($_POST['email']) or $_POST['email']=="")
{
    echo "请输入你的公司邮箱地址";exit;
}
$email = $_POST['email'];

$regex = "#^[\w!$%&'*+/=?^_`{|}~-]+(?:\.[\w!$%&'*+/=?^_`{|}~-]+)*@520it.com$#";
preg_match($regex,$email,$result);
if(empty($result))
{
    echo "sorry,你的公司邮件地址有误，请重新输入";exit;
}

if(isset($_POST['content']))
{
    $content = $_POST['content'];
}



if(isset($_POST['key_syn']))
{
    echo send_regist_code($randomNum, $email, $emailName1);
}

if(isset($_POST['reregistCodeSyn']))
{
    echo send_regist_code($randomNum,$email,$emailName1,$content);
}
exit;
this2:

if(isset($_POST['usname']) and isset($_POST['content']) and isset($_POST['WiFiCode']) and isset($_POST['emailAddress'])){

    echo send_regist_code($_POST['WiFiCode'],$_POST['emailAddress'],$_POST['usname'],$_POST['content']);
}




?>