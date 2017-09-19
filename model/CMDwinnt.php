<?php class CMDwinnt extends sysCmd{

//----------------------这里用于检测可用、已用的端口，杀端口进程的函数------------华丽的分割线---------------------------------

    public function checkIsSettingPort($port){
        $res = self::find_port_pid($port);

        $res = (isset($res) and !empty($res))?true:false;
        return $res;

    }

    public function is_port_exist($port){

        $portInfo = $this->find_port_pid($port);
        $returnRes = (!empty($portInfo))?true:false;
        return $returnRes;
    }

    public function kill_specify_nodeById($pid){

        if(!empty($pid)){

            if(is_array($pid)){
                $cmd='';
                foreach($pid as $value)
                {
                    $cmd.="tskill $value\r\n";
                }
            }
            else{
                $cmd = "tskill $pid\r\n";
            }

            $path = path();
            file_put_contents($path."2.bat",$cmd);
            $cmd1 = "schtasks /Run /TN kill_all_node";
            exec($cmd1,$msg);
            if(!empty($msg)){
                echo "成功关闭指定的pid程序";
            }
            else{
                echo "未能成功关闭指定的pid程序";
            }
        }
        else
        {
            echo "未能成功关闭指定的pid程序";
        }

    }

    public function find_port_pid($port){
        $cmd = "netstat -ano|findstr \"0.0.0.0:$port\"";
        exec($cmd,$return_msgInfo);
        $i=0;
        if(!empty($return_msgInfo)){
            foreach($return_msgInfo as $value)
            {
                preg_match("#0.0.0.0:$port\s#",$value,$res1);
                if(isset($res1[0])) break;
                $i++;

            }

        }

        if(!empty($res1[0])){
            preg_match("#\s+\d+$#",$return_msgInfo[0],$res);
        }

        $res = (isset($res) and !empty($res))?$res[0]:"";
        return $res;
    }

    //----------------------这里关于设置手动/自动更新评价状态的函数------------华丽的分割线---------------------------------


    public function timer_for_stop_estimate_all($matchTest)
    {
        if(!empty($matchTest))
        {
            print_r($matchTest);
            //停止所有端口！！一样的，写一个任务计划，因为这个权限最高。创建一个CMD.bat
            //首先是查询node的所有pid进程，然后存起来然后用命令杀掉。
            $i=1;
            exec('tasklist /FI "imagename eq node.exe"',$info);

            foreach($info as $value)
            {   $value = iconv("GBK","UTF-8",$value);
                preg_match('#node.exe\s+\d*#',$value,$match);
                if(!empty($match))
                {
                    $row[] = $match[0];
                }
                echo "<br>";
                $i++;
            }

            $i=1;
            if(isset($row)and !empty($row))
            {
                echo "<br>";
                $cmd ="";
                foreach ($row as $value)
                {
                    preg_match('|\s*[0-9]+$|', $value, $match);
                    echo "tskill " . $match[0] . "<br>";
                    $match[0] = trim($match[0]," ");
                    $pid = $match[0];

                    $cmd .= "tskill $pid\r\n";
                    $i++;
                }
                $path = dirname($_SERVER['DOCUMENT_ROOT']);
                file_put_contents("$path/pingjia-jiangshi/2.bat",$cmd);
                //下面应该有待写一个杀进程的计划任务！！

            }
            else
            {echo "no node is running!!";}

            self::stop_estimate_all(username,password);
        }
        else
        {
            echo "Nothing!!";
        }

        stop_exec("删除执行完成！！","managerSql");
    }

    //----------------------这里关于设置window防火墙的函数------------华丽的分割线---------------------------------

    public function FW_detect($port)
    {
        $pingjia = '"pingjia-';
        $pingjia .="$port";
        $pingjia .='"';
        exec("netsh advfirewall firewall show rule name=$pingjia",$cmd);
        return $cmd;

    }

    public function FW_get_class_ip_address($classNumber)
    {

        $res = self::getValuesById($classNumber,'classroom','id');
        $row['ipAddress'] = (!empty($res))?$res['values'][0]['ipAddress']:"";
        return $row['ipAddress'];
    }

    public function FW_add($port,$classNumber)
    {
        $ip = self::FW_get_class_ip_address($classNumber);
        $pingjia = '"pingjia-';
        $pingjia .=$port;
        $pingjia .='"';
        $res = self::FW_detect($port);
        if(isset($res[5]))
        {
            exec("netsh advfirewall firewall set rule name=$pingjia new remoteip=192.168.$ip.0/24");
        }
        else
        {
            exec("netsh advfirewall firewall Add rule name=$pingjia dir=in protocol=tcp localport=$port action=allow remoteip=192.168.$ip.0/24");
        }


    }

//----------------------这里关于设置window的任务计划生成的函数------------华丽的分割线---------------------------------
    public function create_cmd($port)
    {
        $filenameH = path()."TM2015";

        //2016-07-13---取消放置WWW配置启动文件了，另外设计文件去创建！！
        /*if(!file_exists($filenameH))
        {
            echo "error--创建多开配置文件失败！！<br>$filenameH/bin/ ,请检查这个文件对象的位置是否存在相应的文件夹！";exit;
        }*/
        $filename = $filenameH."/start-$port.bat";
        $path = path();
        preg_match('|^[a-zA-Z]:|',$path,$root);
        $rootPath = $root[0];
        $content = $rootPath."
cd $filenameH
node bin/www-$port ";


        if(!file_exists($filename))
        {
            @file_put_contents("$filename", $content);
        }
        return $filename;
    }

    public function create_schtasks($port)
    {
        defined('create_estimate_startPort_add_schtask') or define('create_estimate_startPort_add_schtask','no');
        $username = username;
        $passwd = password;
        $php_exe_position = php_exe_position;
        $website = dirname(__DIR__).DIRECTORY_SEPARATOR;
        $drives_name = substr($website,0,2);

        $pingjia_dir = path()."TM2015/time_auto_estimate_stop.bat";
        $estimate_file = $website."estimate_stop.php";

        $exist1 = self::exist_schtasks_name('timer_auto_estimate_stop');
        if(!$exist1)
        {
            if($username!='' and $passwd!='')
            {
                $content = <<<CONENT
$drives_name
cd $website
$php_exe_position $estimate_file
CONENT;

                @file_put_contents($pingjia_dir,$content);
                if(!file_exists($pingjia_dir))
                {
                    echo "no such as time_auto_estimate_stop.bat,make sure you have create such as directory";
                    goto next;
                }
                $cmd2 = "schtasks /create /TN timer_auto_estimate_stop /ST 09:00 /DU 12:00 /RI 10  /SC DAILY /TR $pingjia_dir /RU $username /RP $passwd";
                exec("$cmd2",$result);
                //然后再增加一个额外的到时候就全部的node进程杀屎
                $cmd2 = "schtasks /create /TN timer_auto_estimate_stop_and_kill_all_node /ST 23:11  /SC DAILY /TR $pingjia_dir /RU $username /RP $passwd";
                exec("$cmd2");
            }

        }
        next:
        $exist = self::exist_schtasks($port);
        if(!isset($exist) or $exist =="")
        {
            //这里这里一步的话，里面涉及的是究竟是选用window的bat脚本还是linux的python脚本。
            //这里到了后面需要详细的判断步骤，暂时的话，就只是考虑window的bat脚本。

            //2016-07-13取消create_cmd里面的某些功能！！！另外设置功能！！！
            $cmd = self::create_cmd($port);


            //取消下面这个检测WWW配置文件是否存在的功能，另外设计一个功能用于产生WWW配置文件。
            /*if(!empty($cmd))
            {
                if(!file_exists($cmd))
                {
                    echo "---error---failed to create a bat for schtsks use,please<br> 检查服务器根目录的上一级是否存在pingjia-jiagshi等等文件夹！例如根目录的服务器的绝对路径是www，那么这个www的同级文件夹中是否存在上面所说的文件夹。！";exit;
                }
            }*/
            if(create_estimate_startPort_add_schtask!=NULL and create_estimate_startPort_add_schtask=='yes')
            {


                $cmd1 = "schtasks /create /TN pingjia-$port /ST 10:00  /SC ONCE /TR $cmd /RU $username /RP $passwd";
                exec("$cmd1",$result);
                foreach($result as $value)
                {
                    preg_match("/pingjia-$port/",$value,$chars);
                }
                if(!empty($chars))
                {

                }
                else
                {
                    echo "create schtasks failed,some reason my case this:<br>wrong passwd and username,or both empty!<br>cation!when this problem happing,please the schtasks,if the pingjia-port have create success,please setting the globalconfig where create_xxx=NO!thanks!";
                }


            }

        }

    }

    public function exist_schtasks($port)
    {
        $cmd = "schtasks /query /TN pingjia-$port";
        exec("$cmd",$cmd1);
        foreach ($cmd1 as $value) {
            preg_match("/pingjia-$port/",$value,$chars);
        }
        if(isset($chars))
        {return $chars;}
        else
        {return "";}
    }

    public function exist_schtasks_name($name,$port='')
    {
        $name = $name.$port;
        $cmd = "schtasks /query /TN ".$name;
        exec("$cmd",$cmd1);
        foreach ($cmd1 as $value) {
            preg_match("/$name/",$value,$chars);
        }
        if(isset($chars))
        {return $chars;}
        else
        {}
        return '';
    }

    public function run_schtasks($port,$if_run='yes')
    {
        self::create_schtasks($port);
        if($if_run=='yes'){
            $cmd = "schtasks /Run /TN pingjia-$port";
            exec("$cmd",$result);
        }

    }


    //----------------------这里关于设置初始化、恢复设置评价程序的函数------------华丽的分割线---------------------------------

    public function setUpAllPortSchtasks($port)
    {
        $EndPort = $port+8;
        for($i = $port;$i<=$EndPort;$i++){
            self::run_schtasks($i,'no');
        }
    }

    public function renew_bat_file(){

        $path = path();
        preg_match('|^[a-zA-Z]:|',$path,$rootPath1);
        $rootPath = $rootPath1[0];
        $filePath = path()."TM2015";
        $cmd = $rootPath."
    cd $filePath/bin
    del * /Q
    cd $filePath
    del *.bat /Q

    ";
        file_put_contents($filePath."/renew.bat",$cmd);
        exec($filePath."/renew.bat",$info1);
        //执行删除bat文件

        $cmd_for_del2 = "schtasks /delete /TN kill_all_node /F";
        $cmd_for_del3 = "schtasks /delete /TN timer_auto_estimate_stop /F";
        $cmd_for_del4 = "schtasks /delete /TN timer_auto_estimate_stop_and_kill_all_node /F";

        exec($cmd_for_del2,$info3);
        exec($cmd_for_del3,$info4);
        exec($cmd_for_del4);

    }

    public function renew_bat_schtask($port)
    {

        //然后继续执行删除用于启动任务计划的计划名
        $cmd_for_del = "schtasks /delete /TN pingjia-$port /F";
        /*$cmd_for_del1 = "schtasks /delete /TN lastest-estimate-$type1 /F";*/

        exec($cmd_for_del,$info);
        /*exec($cmd_for_del1,$info1);*/

    }

    ////----------------------这里关于当前的所有的评价对象并且停止并关闭所有进程的函数------------华丽的分割线---------------------------------

    public function config_kill_node()
    {
        $i = 1;
        exec('tasklist /FI "imagename eq node.exe"', $info);

        foreach ($info as $value) {
            $value = iconv("GBK", "UTF-8", $value);
            preg_match('#node.exe\s+\d*#', $value, $match);
            if (!empty($match)) {
                $row[] = $match[0];
            }
            echo "<br>";
            $i++;
        }

        $i = 1;
        if (isset($row) and !empty($row)) {
            echo "<br>";
            $cmd = "";
            foreach ($row as $value) {
                preg_match('|\s*[0-9]+$|', $value, $match);
                echo "tskill " . $match[0] . "<br>";
                $match[0] = trim($match[0], " ");
                $pid = $match[0];

                $cmd .= "tskill $pid\r\n";
                $i++;
            }
            $path = path();
            file_put_contents($path."2.bat", $cmd);
        }
    }

    public function stop_estimate_all($username,$passwd)
    {
        $path = path()."2.bat";
        $cmd = "schtasks /query /TN kill_all_node";
        exec("$cmd",$cmd1);
        foreach ($cmd1 as $value)
        {
            preg_match("/kill_all_node/",$value,$chars);
        }
        if(isset($chars))
        {
            self::config_kill_node();
            $cmd3 = "schtasks /run /TN kill_all_node";
            exec($cmd3,$info);
        }
        else
        {
            //这里就准备开始创建BAT停止的规则
            if($username!='' and $passwd!='')
            {

                $cmd1 = "schtasks /create /TN kill_all_node /ST 23:00  /SC ONCE /TR $path /RU $username /RP $passwd";
                exec("$cmd1",$result);
            }
            self::config_kill_node();
            $cmd3 = "schtasks /run /TN kill_all_node";
            exec($cmd3,$info);
        }

    }


}
?>