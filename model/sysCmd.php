<?php class sysCmd extends db{

    //这里是连个sysCmd的父类，这里可以放置两个子类的共性在这里，提高利用率。
    public $systemType = null;



    public function the_lastest_estimate($port)
    {
        $server_ip = server_ip_address;
        $typeName = estimate_type($port);

        $content="<p>正在进行$typeName-的评价,请同学们根据自己的班级选择评价</p><hr>";
        for($port1 =$port;$port1<=$port+7;$port1++){
            //exec("tcp.exe -w 0.2 -n 1 127.0.0.1 $port1",$res);
            //遇到特定的，需要区别不用系统中来命令来操作的话就使用子类来处理。！！
            $res = $this->is_port_exist($port1);
            if($res)
            {
                $output_test3 = detect_isset_estimate($port1);
                if (isset($output_test3[0]->data->className))
                {	 $cName = $output_test3[0]->data->className;
                    $tName = $output_test3[0]->data->teacherName;
                    $content.="<span style='font-size:20px;'><a  href='http://$server_ip:$port1' target='_blank'>$typeName:$tName--班级:$cName</a></span><br><br>";
                }

                if (!isset($output_test3[0]->data->className))
                {
                    estimate_stop($port1);
                }
            }

        }
        return $content;
    }

    public function estimating_html($anyword,$port='')
    {
        $postion = iisShowDir;

        if($port=='')
        {
            $list = $this->getValuesById('0','port_type','tid');

        }
        else
        {

            $listTemp = $this->query("select * from port_type where tid='0' and port='$port'");
            $list = $listTemp;
        }

        if(empty($list['values']))
        {
            echo "error--,not defined tpye port,please check the port of database is exist?";exit;
        }

        foreach($list['values'] as $value)
        {
            $type_name[] = $value['type'];
            $type_port[] = $value['port'];
            $type_rname[] = $value['Rname'];
        }
        $i=0;
        foreach($type_port as $value1)
        {
            $obj_name = $type_name[$i];
            $port_temp = $value1;
            $type_temp = $type_rname[$i];
            $header2 = self::the_lastest_estimate($port_temp);
            $header1 = <<<HTML

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
   <meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-control" content="no-cache">
<meta http-equiv="Cache" content="no-cache">
    <title>SEEMYGO-$obj_name--评价</title>
</head>
<body><center>

		$header2
</center>
</body>
</html>
HTML;
            $content = $header1;
            @file_put_contents("$postion/$type_temp.html",$content);
            if(!file_exists("$postion/$type_temp.html"))
            {
                echo "error--create failed,no such as directory or file,$postion/,check it !";
            }

            $i++;}

    }






}
?>