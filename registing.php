<?php
include 'conn.php';
include 'function/function.php';
header('content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
$time = date("Y-m-d H:i:s",time());


//设置一个特殊的密令，用于服务端发过来用来同步成功注册信息用户的信息。

if(isset($_POST['key_syn']))
{
    if($_POST['key_syn']=='ttyderfre4e345wer345wer34ee')
    {
        //检测当前提交表单里面的数据是否含有相关的参数
        if( !isset($_POST['bzr-username']) or !isset($_POST['password']) or !isset($_POST['email']) or !isset($_POST['registerCode']) or !isset($_POST['codeImage']))
        {
            stop_exec("注册数据提交参数错误！！");
        }

        //流程到这里的话，应该就是指定参数已经设置好了，现在就是跳到正则表达式检查
        goto syn;
    }
    else
    {
        stop_exec('失败！！');
    }
}



//首先检测当前有没有设置reregist，该参数主要用于检测当前是不是密码重置的步骤
if(isset($_POST['reregist']))
{
    if($_POST['reregistCodeSyn']!="CCCXxsaddddfgte#")
    {
        echo "错误！！！";exit;
    }
    if(  !isset($_POST['password']) or !isset($_POST['email']) or !isset($_POST['reregisterCode']) or !isset($_POST['codeImage']))
    {

        stop_exec("重置数据提交参数错误！！");
    }
    goto next;
}
if( !isset($_POST['bzr-username']) or !isset($_POST['password']) or !isset($_POST['email']) or !isset($_POST['registerCode']) or !isset($_POST['codeImage']))
{
    stop_exec("注册参数错误！！");
}
next:
syn:
$regexName = "#^[_A-Za-z0-9\x{4e00}-\x{9fa5}]{2,20}$#u";
$regexPass = "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,25}/";
$regexRegisterCode = "/^[a-z0-9A-Z]{6}$/";
$regexCodeImage = "/^[a-z0-9A-Z]{4}$/";
$regexEmail = "#^[\w!$%&'*+/=?^_`{|}~-]+(?:\.[\w!$%&'*+/=?^_`{|}~-]+)*@520it.com$#";

if(isset($_POST['bzr-username']))
{
    preg_match($regexName, $_POST['bzr-username'], $name);
    if (empty($name)) {
        stop_exec("两个字符以上,只能有中文,字母,数字,下划线");
    }

    $nameT = $name[0];
   /* if(!isset($_POST['reregist'])  )
    {*/
        $nameCopy = $conn->query("select * from admin where username='$nameT'");
        $nameCopy = $nameCopy->num_rows;
        //这里用于检测传送数据方是不是服务器，如果是的话，发现用户名已经重复的情况下，
        //检查当前的请求数据里面的邮箱是不是属于当前的用户

    if($nameCopy=='0')
    {
        echo "<script>alert('这个嘛，客户端不存在你的信息，现在为你添加啊！！')</script>";

        //正在为你添加的时候就需要把当前的重置密码状态取消，变成了新的注册模式
        unset($_POST['reregist']);unset($_POST['reregistCodeSyn']);
        $_POST['key_syn']='ttyderfre4e345wer345wer34ee';

    }


       /* if(isset($_POST['key_syn']))
        {
            if ($_POST['key_syn'] == 'ttyderfre4e345wer345wer34ee')
            {*/
                $emailR = $conn->query("select * from admin where username='$nameT'");
                $emailR = $emailR->fetch_assoc();
                $emailR1 = $emailR['email'];
                if($_POST['email']==$emailR1)
                {
                goto syn5;
                }
        /*    }
        }*/
echo "this is postemail".$_POST['email']."and this is database email".$emailR1;
        if ($nameCopy != '0') {
            stop_exec("客户端用户名已经存在！");
        }/*}*/
}

syn5:


preg_match($regexPass,$_POST['password'],$pw);
if(empty($pw))
{
    stop_exec("必须至6到25个字符长，并且至少包含一个数字，一个大写字母和一个小写字母");
}

$password = md5($pw[0]);

/*if(isset($_post['']))*/

if(isset($_POST['key_syn']))
{
    goto syn1;
}

if(isset($_POST['reregistCodeSyn']))
{
    goto syn1;
}

if(isset($_POST['registerCode'])) {
    preg_match($regexRegisterCode, $_POST['registerCode'], $registerCode);
    if (empty($registerCode)) {
        stop_exec("请输入完整的注册码");
    }
}

syn1:

preg_match($regexEmail,$_POST['email'],$email);
if(empty($email))
{
    stop_exec("请输入正确的公司邮箱");
}

$emailT = $email[0];
if(isset($_POST['key_syn']) or isset($_POST['reregistCodeSyn']))
{
    goto syn2;
}

preg_match($regexCodeImage,$_POST['codeImage'],$codeImage);
if(empty($codeImage))
{
    stop_exec("请输入完整的验证码");
}

syn2:

if(isset($_POST['reregist']))
{
    goto regist;
}
$emailName="";
$emailName = $conn->query("select * from admin where email='$emailT'");
$num_rows =  $emailName->num_rows;

if($num_rows =="1")
{
    //这里出现相同的邮箱的时候就需要检查一下又是不是服务端又发了一个重新覆盖之前
    //用户信息，是的话，就准备跳下一步更新！！

    if(isset($_POST['key_syn']))
    {
        if ($_POST['key_syn'] == 'ttyderfre4e345wer345wer34ee')
        {
           /* $emailR = $conn->query("select * from admin where username='$nameT'");
            $emailR = $emailR->fetch_assoc();
            $emailR1 = $emailR['email'];
            if($_POST['email']==$emailR1)
            {*/
               $result = $conn->query("update admin set password='$password',username='$nameT' where email='$emailT'");
                echo "已经成功同步了用户的账号信息！！";exit;
            /*}*/
        }
    }


    stop_exec("你的邮箱地址似乎失效，可能是你的邮箱已经注册过了,请联系网管");

}
if(isset($_POST['key_syn']))
{
    goto syn3;
}

session_start();
if($_SESSION['verification']!=md5($codeImage[0]))
{
    stop_exec("验证码错误");
}

syn3:
$email = $email[0];
$emailExist = $conn->query("select * from user where email='$email'");
$emailExist = $emailExist->num_rows;
if($emailExist=='0')
{
    stop_exec("邮箱不存在！");
}



$emailCode = $conn->query("select * from user where email='$email'");
$emailCode = $emailCode->fetch_assoc();
$emailCodeNum = $emailCode['registCode'];
$userDepartment = $emailCode['department'];
if(isset($_POST['key_syn']))
{
    goto syn4;
}
if($emailCodeNum!=md5($registerCode[0]) or ($emailCode['registExpired'] < $time))
{
    stop_exec("客户端注册码不正确，或者可以尝试重新获取注册码！");
}

syn4:

$result = $conn->query("insert into admin(username,password,email,department) values('$nameT','$password','$email','$userDepartment')");

if($result)
{
    $nameT = addslashes($nameT);
    $res1 = $conn->query("select * from admin where username='$nameT'");
    $userinfo = $res1->fetch_assoc();
   /* $_SESSION['xz_uid'] = addslashes($userinfo['id']);
    $_SESSION['xz_username'] = addslashes($nameT);
    $_SESSION['xz_department'] = addslashes($userinfo['department']);
    $_SESSION['xz_email'] = addslashes($userinfo['email']);*/
//服务端的这里的话每次成功以后都需要将刚刚注册成功的用户信息同步到每一个客户端。
    //而且最后反馈一个时候成功的信息


    echo "<script>alert('恭喜！注册成功，正在为了跳转');window.location.href='index.php'</script>";exit;
}
else
{
    stop_exec("注册过程中发生意外，请联系网管啦！");
}


session_start();
if($_SESSION['verification']!=md5($codeImage[0]))
{
    stop_exec("验证码错误");
}
regist:


$emailT = $email[0];
$emailName="";
$emailName = $conn->query("select * from admin where email='$emailT'");
$num_rows =  $emailName->num_rows;

if($num_rows !="1")
{
    stop_exec("没有找到你的注册信息,请联系网管");
}

//下面这个设置是针对客户端应用于服务端发来的信息处理跳过相关的检验！！
if(isset($_POST['reregist']))
{
    goto syn6;
}


//d对比注册码是否正确！！
$emailCode = $conn->query("select * from user where email='$emailT'");
$emailCode = $emailCode->fetch_assoc();
$emailCodeNum = $emailCode['reregistCode'];
$userDepartment = $emailCode['department'];
if($emailCodeNum!=md5($_POST['reregisterCode']) or ($emailCode['reregistExpired'] < $time) )
{
    stop_exec("客户端注册码不正确，或者可以尝试重新获取注册码！");
}
syn6:
$result = $conn->query("update admin set password='$password' where email='$emailT'");

if($result)
{

    $nameT = addslashes($nameT);
    $res1 = $conn->query("select * from admin where username='$nameT'");
    $userinfo = $res1->fetch_assoc();
    /*$_SESSION['xz_uid'] = addslashes($userinfo['id']);
    $_SESSION['xz_username'] = addslashes($nameT);
    $_SESSION['xz_department'] = addslashes($userinfo['department']);
    $_SESSION['xz_email'] = addslashes($userinfo['email']);*/

    echo "<script>alert('恭喜！注册成功，正在为了跳转');window.location.href='index.php'</script>";exit;
}
else
{
    stop_exec("重置密码过程中发生意外，请联系网管啦！");
}


?>