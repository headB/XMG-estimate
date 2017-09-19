<?php
header("content-type:text/html;charset=utf-8");

require_once "email.class.php";
date_default_timezone_set("Asia/Shanghai");

$content = file_get_contents("http://xmg520.cn/xz/expired_computer.php");

$contentStrLeng = mb_strlen($content);

if($contentStrLeng < 50)
{echo "<h3>不需要执行发送任务！</h3>"; exit;}

$mailcontent = $content;
//******************* 配置信息 ********************************
$smtpserver = "smtp.ym.163.com";//SMTP服务器
$smtpserverport =25;//SMTP服务器端口
$smtpusermail = "lizhixuan@520it.com";//SMTP服务器的用户邮箱
$smtpemailto = "lizhixuan@520it.com" /*"linqiuping@520it.com,liushuangxiang@520it.com,lutian@520it.com,majunfeng@520it.com,mengting@520it.com,yangfang@520it.com,renjing@520it.com"*/;//发送给谁
$smtpuser = "lizhixuan@520it.com";//SMTP服务器的用户帐号
$smtppass = "lizhixuan123";//SMTP服务器的用户密码
$mailtitle = "---广州小码哥电脑租赁到期通知---";//邮件主题
$mailtitle = "=?UTF-8?B?".base64_encode($mailtitle)."?=";



$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
//************************ 配置信息 ****************************
$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
$smtp->debug = false;//是否显示发送的调试信息
$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype/*,"lizhixuan@520it.com,fengwenjin@520it.com"*/);

echo "<div style='width:300px; margin:36px auto;'>";
if($state==""){
    echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";

    exit();
}
echo "恭喜！邮件发送成功！！";

echo "</div>";
?>