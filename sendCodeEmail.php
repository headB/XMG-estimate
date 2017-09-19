<?php

require_once "email.class.php";

defined('emailSender') or define('emailSender','');
defined('emailPasswd') or define('emailPasswd','');

$emailSender = emailSender;
$emailPasswd = emailPasswd;

if(empty($emailSender) or empty($emailPasswd)){
    echo "缺少参数，请联系网管填写好邮件发件人的账号密码";exit;
}


function send_regist_code($registCode,$emailAdress,$name,$addContent="")
{

    header("content-type:text/html;charset=utf-8");
    date_default_timezone_set("Asia/Shanghai");
    $today = date("Y-m-d");
    echo $today;
    /*$name1 = $emailAdress;*/
    //******************** 配置信息 ********************************
    $smtpserver = "smtp.ym.163.com";//SMTP服务器
    $smtpserverport =25;//SMTP服务器端口
    $smtpusermail = emailSender;//SMTP服务器的用户邮箱
    $smtpemailto = $emailAdress;//发送给谁
    $smtpuser = emailSender;//SMTP服务器的用户帐号
    $smtppass = emailPasswd;//SMTP服务器的用户密码
    $mailtitle = "---广州小码哥行政部用户注册页面---";//邮件主题
    $mailtitle = "=?UTF-8?B?".base64_encode($mailtitle)."?=";
    $mailcontent = "<center><h3>"."-亲爱的 $name -老师，你好:"."</h3></center>";//邮件内容

    if($addContent=="")
    {
    $mailcontent.= "<center><br>下面为你这次用户注册申请用的注册码：<span style='color:green;' ><b> $registCode </b></span>。<br>注册码的有效时间为半小时内，请尽量在半小时内操作完成。<br>如果你没有申请用户注册码，你可以忽略该邮件，对你产生的干扰我们深感抱歉。</center>";
    }
    else
    {
        $mailcontent.= "<center><br>下面为你这次用户密码重置的地址：<span style='color:green;' ><b> $addContent </b></span>。注意：该链接到达你邮箱以后30分钟之内操作有效，超时无效，需要重新申请<br>如果你没有申请用户注册码，你可以忽略该邮件，对你产生的干扰我们深感抱歉。</center>";
    }

    $mailcontent.= "</tbody></table><br><center>技术支持POWER BY ©行政，更多请访问<a href='http://xmg520.cn'>xmg520.cn</a></center><br><center>如有其他问题或者建议可以联系网管QQ：<b>2885304737</b></center>";








    $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
    //************************ 配置信息 ****************************
    $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false;//是否显示发送的调试信息
    $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);


    if($state==""){
        echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";

        exit();
    }
    echo "恭喜！邮件发送成功！！";





}


?>