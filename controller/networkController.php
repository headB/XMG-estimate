<?php

class networkController extends crypt1 {

    public $aclRule = null;
    public $connection = null;
    public $classInfo = null;
    public $filePath = null;
    public $passwd = 'ho8RaD7KohUvy7f2M/AWjQ==';

    public function  __construct()
    {
        parent::__construct();
        $this->connectHuawei();
        /*$passwd1  = $this->decryptStr($this->passwd);
        $this->connection = ssh2_connect("192.168.113.254", 22);
        ssh2_auth_password($this->connection,"xmg", "xmg175207");*/

    }

    public function connectHuawei(){

        $position = dirname(__DIR__).DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR;

        $ftpconn = ftp_connect("192.168.113.254") or die("Could not connect");
        ftp_login($ftpconn,"xmg","xmg175207");
        ftp_get($ftpconn,$position."vrpcfg.zip","vrpcfg.zip",FTP_BINARY);

        ftp_close($ftpconn);

        $zip = zip_open($position."vrpcfg.zip");

        while($zips = zip_read($zip)){
            zip_entry_open($zip,$zips);
            $this->aclRule =  zip_entry_read($zips,204800);
        }

        
        file_put_contents($position.'vrpcfg.zip','1');

        $this->filePath = $position."telnet.cmd";

      /*  $res = shell_exec($cmd);
        print_r($res);

        $cmd=<<<html
dis acl 3030
html;
        $stream = ssh2_exec($this->connection,"$cmd");

        stream_set_blocking( $stream, true );
        print_r(stream_get_contents($stream));*/

        /*$dio_stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);  //获得标准输入输出留
        $err_stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);  //获得错误输出留
        stream_set_blocking($err_stream, true);
        stream_set_blocking($dio_stream, true);
        $result_err = stream_get_contents($err_stream);
        $result_dio = stream_get_contents($dio_stream); //获取流的内容，即命令的返回内容
        echo $result_dio;
        echo $result_err;
        fclose($stream);*/
    }

    public function create_win_bat(){

        $position = dirname(__DIR__).DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR;
        preg_match('#\w:#',$position,$diskSN);
        $disk = $diskSN[0];
        $cmd_bat=<<<BAT
$disk \r\n
cd $position \r\n
expect telnet.cmd \r\n
BAT;

        file_put_contents($position."telnetStartUp.bat",$cmd_bat);

    }

    public function create_cmd_telnet($commandArray){

        $OS = defineSystemType();

        $underLine='';
        if($OS=='winnt'){
            $underLine = "\r\n";
        }
        if($OS=='linux'){
            $underLine = "\n";
        }

        $commands = '';
        if(is_array($commandArray)){

            foreach($commandArray as $command){


                $commands .= "send \"$command\\r\"".$underLine;

            }

        }

        $position = dirname(__DIR__);
        $cmd=<<<CMD
#!/usr/bin/expect
set timeout 3
spawn telnet 192.168.113.254
expect "username:"
send "xmg\\r"
expect "password:"
send "xmg175207\\r"
send "sys\\r"
$commands
send "q\\r"
send "save\\r"
expect "Are you sure to continue?\[Y/N\]"
send "y\\r"
send "q\\r"
expect eof

CMD;

        file_put_contents($position.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."telnet.cmd",$cmd);

    }

    public function create_cmd_ssh($classRoom){
        $position = dirname(__DIR__);
        $cmd=<<<CMD
#! /usr/bin/expect
set timeout 3
spawn ssh -o StrictHostKeyChecking=no xmg@192.168.113.254
expect "password:"
send "xmg175207\\r"
expect "<s5700>"
send "sys\\r"
#expect "\[5700\]"
send "dis acl $classRoom\\r"
send "         "
expect eof

CMD;

        file_put_contents($position.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."telnet.cmd",$cmd);

                                                }

    public function create_cmd_ssh_wscript($commandArray){

        $commands = '';
        if(is_array($commandArray)){

            foreach($commandArray as $command){

                $commands .= "echo WScript.Sleep 300 >>telnet_tmp.vbs\r\n";
                $commands .= "echo sh.SendKeys\"$command {ENTER} \" >>telnet_tmp.vbs\r\n";

            }

        }

        $position = dirname(__DIR__);
        $cmd=<<<BAT
@echo off
echo set sh=WScript.CreateObject("WScript.Shell")>telnet_tmp.vbs
echo WScript.Sleep 300 >>telnet_tmp.vbs
echo sh.SendKeys"ssh -o StrictHostKeyChecking=no xmg@192.168.113.254{ENTER}">>telnet_tmp.vbs
echo WScript.Sleep 6000 >>telnet_tmp.vbs
echo sh.SendKeys"xmg175207" >>telnet_tmp.vbs
echo WScript.Sleep 300 >>telnet_tmp.vbs
echo sh.SendKeys"{ENTER}" >>telnet_tmp.vbs
echo WScript.Sleep 300 >>telnet_tmp.vbs
echo sh.SendKeys"sys {ENTER}">>telnet_tmp.vbs
echo WScript.Sleep 300 >>telnet_tmp.vbs
$commands
echo WScript.Sleep 600 >>telnet_tmp.vbs
echo sh.SendKeys"q {ENTER}">>telnet_tmp.vbs
echo WScript.Sleep 300 >>telnet_tmp.vbs
echo sh.SendKeys"save {ENTER}">>telnet_tmp.vbs
echo WScript.Sleep 300 >>telnet_tmp.vbs
echo sh.SendKeys"y {ENTER}">>telnet_tmp.vbs
echo WScript.Sleep 6000 >>telnet_tmp.vbs
echo sh.SendKeys"q {ENTER}">>telnet_tmp.vbs
echo WScript.Sleep 2000 >>telnet_tmp.vbs
echo sh.SendKeys"exit {ENTER}">>telnet_tmp.vbs
start cmd
cscript //nologo telnet_tmp.vbs
del telnet_tmp.vbs
BAT;

        file_put_contents($position.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."telnet.cmd",$cmd);

    }

        public function index(){

             $classInfo = $this->getValuesByTable('classroom');
            $this->classInfo = $classInfo['values'];


        }


    //这个函数是用于window专用的启动执行华为acl网络上网行为的命令。
    public function run_schtasks()
    {

        $OS = defineSystemType();

        if($OS=='winnt'){
            $cmd = "schtasks /Run /TN telnet-cmd";
            exec("$cmd",$result);
        }

        if($OS=='linux'){
            chmod($this->filePath,0777);
            exec("$this->filePath",$hh);

        }

        }

    //这个是用于匹配acl规则的。
    public function get_value_acl_rule($aclNumber){

            preg_match("#(acl number $aclNumber)[\d\w\s\.-]+#",$this->aclRule,$classAcl);

            if(!empty($classAcl[0])){

                //这里匹配出具体的acl number的规则，算出所有的rule的号码
                preg_match_all("#rule [\d]+#",$classAcl[0],$aclRule);

                //这里匹配出具体的acl number的规则，将每一条的rule转换到单独的数组
                $aclRuleArray = explode('rule',$classAcl[0]);


                //这里计算出最后的rule的具体号码
                $countRule = count($aclRule[0]);
                preg_match("#[\d]+#",$aclRule[0][$countRule-1],$aclNumberTotal);

                //这里算出有没有设置定时上网的规则
                preg_match_all("#rule [\d]+ permit ip time-range#",$classAcl[0],$aclRule1);
                if(!empty($aclRule1[0])){
                $res['timerOnline'] = 'yes';
                }else{
                    $res['timerOnline'] = 'no';
                }

                //这里是匹配是否集体上网
                /*foreach($aclRuleArray as  $value){
                    $value = trim($value);
                    preg_match('#\d+ permit ip$#',$value,$aclRule2);
                    if(!empty($aclRule2[0])){
                        $res['allOnline'] = 'yes';
                        break;
                    }else{
                        $res['allOnline'] = 'no';
                    }

                }*/

                $res['allOnline']='no';

                //这里是另外一种匹配是否集体上网的方式
                $aclRuleNumber='';
                $aclDenyRuleNumber='';
                if($res['allOnline']=='no'){
                    $i=0;
                    $allOnline=0;


                    foreach($aclRuleArray as  $value){

                        $value = trim($value);
                        preg_match('#\d+ permit ip source 192.168.\d+.0 0.0.0.127#',$value,$aclRule3);
                        if(!empty($aclRule3[0])){
                            $i++;
                            preg_match('#[\d]{1,3}#',$aclRule3[0],$tt);
                            $aclRuleNumber[]=$tt[0];

                        }
                        preg_match('#\d+ permit ip source 192.168.\d+.64 0.0.0.191#',$value,$aclRule3);
                        if(!empty($aclRule3[0])){
                            $i++;
                            preg_match('#[\d]{1,3}#',$aclRule3[0],$tt);
                            $aclRuleNumber[]=$tt[0];

                            }
                        preg_match('#\d+ permit ip source 192.168.\d+.32 0.0.0.223#',$value,$aclRule3);
                        if(!empty($aclRule3[0])){
                            $i++;
                            preg_match('#[\d]{1,3}#',$aclRule3[0],$tt);
                            $aclRuleNumber[]=$tt[0];

                            }
                    }

                    foreach($aclRuleArray as  $value){

                        $value = trim($value);
                        preg_match('#\d+ deny ip source 192.168.\d+.0 0.0.0.127#',$value,$aclRule4);
                        if(!empty($aclRule4[0])){

                            preg_match('#[\d]{1,3}#',$aclRule4[0],$tt);
                            $aclDenyRuleNumber[]=$tt[0];

                        }
                        preg_match('#\d+ deny ip source 192.168.\d+.64 0.0.0.191#',$value,$aclRule4);
                        if(!empty($aclRule4[0])){

                            preg_match('#[\d]{1,3}#',$aclRule4[0],$tt);
                            $aclDenyRuleNumber[]=$tt[0];

                        }
                        preg_match('#\d+ deny ip source 192.168.\d+.32 0.0.0.223#',$value,$aclRule4);
                        if(!empty($aclRule4[0])){

                            preg_match('#[\d]{1,3}#',$aclRule4[0],$tt);
                            $aclDenyRuleNumber[]=$tt[0];

                        }
                    }


                    if($i==3){
                        $res['allOnline'] = 'yes';
                    }

                }

                $res['aclRuleNumber']=$aclRuleNumber;
                $res['aclDenyRuleNumber']=$aclDenyRuleNumber;
                $res['aclRuleArray'] = $aclRuleArray;
                $res['ruleBottom'] = $aclNumberTotal[0];
                $res['values'] = $classAcl;
            }
        else{
            $res = $classAcl;
        }

            return $res;

    }

    public function allOnline($aclNumber,$ip="example '31'"){

        $info = $this->get_value_acl_rule($aclNumber);

        $lastRuleNumber = $info['ruleBottom'];


        $i1=$lastRuleNumber+1;
        $i2=$lastRuleNumber+2;
        $i3=$lastRuleNumber+3;

        $acl[]="acl $aclNumber";

        if(is_array($info['aclDenyRuleNumber'])){
            foreach($info['aclDenyRuleNumber'] as $value){

                $acl[]="undo rule $value";

            }
            $i1=$lastRuleNumber-2;
            $i2=$lastRuleNumber-1;
            $i3=$lastRuleNumber;
        }

        $acl[]="rule $i1 permit ip source 192.168.$ip.0 0.0.0.127";
        $acl[]="rule $i2 permit ip source 192.168.$ip.64 0.0.0.191";
        $acl[]="rule $i3 permit ip source 192.168.$ip.32 0.0.0.223";
        $acl[]="q";


        $this->create_cmd_telnet($acl);

        $OS = defineSystemType();



        $this->run_schtasks();

    }

    public function allOffline($aclNumber,$ip="example '31'"){

        $ruleNumber='';
        $info = $this->get_value_acl_rule($aclNumber);

        $permitInfo = $info['aclRuleArray'];
        foreach($permitInfo as $value ){

            $value = trim($value);
            preg_match("#\d+ permit ip source 192.168.$ip.0 0.0.0.127#",$value,$aclRule3);
            if(!empty($aclRule3[0])){
                preg_match("#[\d]{1,3}#",$value,$aclRule4);
                $ruleNumber[1]=$aclRule4[0];
            }

            preg_match("#\d+ permit ip source 192.168.$ip.64 0.0.0.191#",$value,$aclRule3);
            if(!empty($aclRule3[0])){
                preg_match("#[\d]{1,3}#",$value,$aclRule4);
                $ruleNumber[2]=$aclRule4[0];
            }

            preg_match("#\d+ permit ip source 192.168.$ip.32 0.0.0.223#",$value,$aclRule3);
            if(!empty($aclRule3[0])){
                preg_match("#[\d]{1,3}#",$value,$aclRule4);
                $ruleNumber[3]=$aclRule4[0];
            }
        }


        if(empty($ruleNumber)){echo "错误，没有需要执行的！！";exit;}

        $acl[] = "acl $aclNumber";
        $acl[] = "undo rule $ruleNumber[1]";
        $acl[] = "undo rule $ruleNumber[2]";
        $acl[] = "undo rule $ruleNumber[3]";
        $acl[] = "rule $ruleNumber[1] deny ip source 192.168.$ip.0 0.0.0.127";
        $acl[] = "rule $ruleNumber[2] deny ip source 192.168.$ip.64 0.0.0.191";
        $acl[] = "rule $ruleNumber[3] deny ip source 192.168.$ip.32 0.0.0.223";
        $acl[] = "q";

        $this->create_cmd_telnet($acl);

        $OS = defineSystemType();


        $this->run_schtasks();


    }

    public function timerOnline($aclNumber){



    }

    public function timerOffline($aclNumber){



    }

    public function switch_operate(){

        foreach($_GET as $key=>$value){

            $key = addslashes($key);
            $value = addslashes($value);

        }

        if(isset($_GET['operate']) and $_GET['operate']=='allOnline'){

            if(isset($_GET['value'])){

                $info = $this->getValuesById($_GET['value'],'classroom','id');
                if(!isset($info['values'][0])){echo "error";exit;}

                $aclNumber = $info['values'][0]['ACL'];
                $ip = $info['values'][0]['ipAddress'];

                $this->allOnline($aclNumber,$ip);

            }

        }

        if(isset($_GET['operate']) and $_GET['operate']=='allOffline'){

            if(isset($_GET['value'])){

                $info = $this->getValuesById($_GET['value'],'classroom','id');
                if(!isset($info['values'][0])){echo "error";exit;}

                $aclNumber = $info['values'][0]['ACL'];
                $ip = $info['values'][0]['ipAddress'];

                $this->allOffline($aclNumber,$ip);

            }

        }

    }







}