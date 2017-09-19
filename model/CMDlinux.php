<?php class CMDlinux extends sysCmd{


    //----------------------这里用于检测可用、已用的端口，杀端口进程的函数------------华丽的分割线---------------------------------

    public function checkIsSettingPort($port){
        $res = self::find_port_pid($port);

        $res = (isset($res) and !empty($res))?true:false;
        return $res;

    }

    public function kill_specify_nodeById($pid){

        if(!empty($pid)){
            if(is_array($pid)){
                foreach($pid as $value){
                    $cmd1 = "sudo kill $value";
                    exec($cmd1,$msg);
                }

            }else{
                $cmd1 = "sudo kill $pid";
                exec($cmd1,$msg);
            }

        }
        else
        {
            echo "no defined pid";
        }

    }

    public function find_port_pid($port){
        $cmd = "sudo netstat -anp|grep 0.0.0.0:$port";
        exec($cmd,$return_msgInfo);
        $i=0;
        if(!empty($return_msgInfo)){

                preg_match("#0.0.0.0:$port\s#",$return_msgInfo[0],$res1);

        }

        if(!empty($res1[0])){
            preg_match("#\s+\d+/node$#",$return_msgInfo[0],$res);
            $resStr = substr($res[0],0,-5);
        }
        else{
            $resStr = "";
        }

        $res = (isset($res) and !empty($res))?$resStr:"";
        return $res;
    }

    public function is_port_exist($port){
        $portInfo = self::find_port_pid($port);
        $returnRes = (!empty($portInfo))?true:false;
        return $returnRes;
    }

    //----------------------这里用于生成、设定防火墙规则的函数------------华丽的分割线---------------------------------

    public function FW_detect($port)
    {
        $cmd = "sudo iptables --list -n --line|grep dpt:$port";
        exec($cmd,$msg);
        if(!empty($msg)){
            foreach($msg as $value){
                preg_match("#^\d+\s+#",$value,$matchRes);
                if(!empty($matchRes)){$msg = $matchRes[0];break;}
            }
        }
        return $msg;

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
        $RNumber = self::FW_detect($port);
        if(!empty($RNumber)){
            $cmd = "sudo iptables -R INPUT $RNumber -p tcp -m tcp -s 192.168.$ip.0/24 --dport $port -j ACCEPT
            sudo /etc/init.d/iptables save
            sudo /etc/init.d/iptables restart
            ";
        }else{
            $cmd = "sudo iptables -I INPUT  -p tcp -m tcp -s 192.168.$ip.0/24 --dport $port -j ACCEPT
            sudo /etc/init.d/iptables save
            sudo /etc/init.d/iptables restart
            ";
        }
        shell_exec($cmd);

    }


//----------------------这里用于调用运行node程序的函数------------华丽的分割线---------------------------------
    public function run_schtasks($port,$if_run='yes'){
        defined('node_exec_position') or define('node_exec_position','');
        $node_position = node_exec_position;
        if(empty($node_position)){echo "error_node_exec_position";exit;}

        $res = preg_replace('#\\+$#','',node_exec_position);
        $res = preg_replace('#//+$#','',$res);
        $res = preg_replace('#\\+$#','',$res);
        $res = $res.DIRECTORY_SEPARATOR."node";
        $node_position = $res;
        $TM2015_position = path()."TM2015";
        $startUpShellLocation = path()."TM2015/startshell/www-".$port.".sh";

        $content=<<<html1
#!/bin/sh
cd $TM2015_position
nohup $node_position bin/www-$port > /var/www/html/nodeInfo.log 2>&1 &
html1;
        file_put_contents($startUpShellLocation,$content);
        chmod($startUpShellLocation,0765);
        exec($startUpShellLocation);
    }

    //----------------------这里关于设置手动/自动更新评价状态的函数------------华丽的分割线---------------------------------
    

    public function timer_for_stop_estimate_all($matchTest)
    {
        $time = date('Y-m-d H:i',time());
        preg_match("#23:\d\d#",$time,$matchTest);

        if(!empty($matchTest)){


            $initPortInfo = self::getInitPort();
            foreach($initPortInfo as $value){

                $portPid = $this->find_port_pid($value);
                $this->kill_specify_nodeById($portPid);

            }

        }

        stop_exec("删除执行完成！！","managerSql");
    }

}

?>
