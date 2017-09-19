<?php class client_estimate_process{

    public $db = NULL;

    public function __construct()
    {
        $this->db = new db;

    }

    public function curl($port,$downTypeDetail,$id){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:$port/grade/download-$downTypeDetail?id=$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function check_estimate_post_status1(){

        $secret = new crypt1();





        $sql = "select * from  `estimate_history` where `post` is null limit 5";
        $estimateInfo = $this->db->query($sql);
        if(empty($estimateInfo['values'])){exit;}

        $returnContent='';
        $content = '';
        foreach($estimateInfo['values'] as $value){

            $content = '';

            $content2='';
            $content3='';

            $classInfoId  = $value['classInfoId'];
            $setting_time = $value['setting_time'];

            unset($value['uniqueNum']);

            $sql_insert_detail = insert($value,'estimate_history');


            $port_info = $this->db->getFartherPortById($value['typeDetail']);


            $type_info = $this->db->getRnamebyPortId($value['typeDetail']);

            /*echo "http://127.0.0.1:$port_info/grade/download-$type_info?id=$classInfoId";echo "<br>";*/
            $time = $value['setting_time'];
            $className = $value['className'];
            $teacherName = $value['teacherName'];
            $t1 = $value['typeDetail'];
            $t2 = $value['sid'];
            /*$contentInfo = $value['content'];*/
            $typeDetail1 = $this->db->getValuesById($t1,'port_type','id');
            $typeDetail = $typeDetail1['values'][0]['type'];

            $content.= "*************************************************************************************\r\n
\n";
            $content .= "老师名字:$teacherName  类型:$typeDetail  班级:$className  评价时间:$time";
            $content .=  "\r\n";
            /*$content .= file_get_contents("http://127.0.0.1:$port_info/grade/download-$type_info?id=$classInfoId");*/
            $content1 = file_get_contents("http://127.0.0.1:$port_info/grade/download-$type_info?id=$classInfoId");

            $content.= $content1;


            $content .= "\r\n";


            $content2 = "insert into estimate_history(`classInfoId`,`sid`,`setting_time`,`teacherName`,`className`,`content`) values('$classInfoId','$t2','$time','$teacherName','$className','$content')";
            $content3 = $secret->cryptStr($content2);
            $returnContent .= $classInfoId."java+";
            $returnContent .= $content3."php+";


        }


        return $returnContent;


    }

    //2017-05-18
    //修改功能，这个功能是放在除了广州以外的所有对学生服务的linux服务器上。
    public function check_estimate_post_status(){

        $remoteConn = new db();
        $remoteConn->dataBase = "xmgcms";
        $remoteConn->WiFi_conn();


        $sql = "select * from  `estimate_history` where `post` is null";
        $estimateInfo = $this->db->query($sql);
        if(empty($estimateInfo['values'])){exit;}

        //如果上面的流程判断没有停止的话，那就是证明有数据需要去处理了。！！
        //还得说一个东西就是，这个需要另外增加一个数据库连接了。因为这个是远程操作数据库了。
        //还有就是，这个就是实例化的好处了，一个对象是一个对象，彼此的对象是相互不会影响的。
        $data = "";
        $dataArray = "";
        //2017-05-19 下面写一个循环，把信息写进远程数据库。
        foreach($estimateInfo['values'] as $value){

            $port_info = $this->db->getFartherPortById($value['typeDetail']);
            $type_info = $this->db->getRnamebyPortId($value['typeDetail']);

            $time = $value['setting_time'];
            $className = $value['className'];
            $teacherName = $value['teacherName'];
            $t1 = $value['typeDetail'];

            $typeDetail1 = $this->db->getValuesById($t1,'port_type','id');
            $typeDetail = $typeDetail1['values'][0]['type'];

            $content="";
            $data['`sid`'] = $value['sid'];
            $data['`who`'] = $value['who'];
            $data['`port`'] = $value['port'];
            $data['`typeDetail`'] = $value['typeDetail'];
            $data['`setting_time`'] = $value['setting_time'];
            $data['`expired_time`'] = $value['expired_time'];
            $data['`classInfoId`'] =  $classInfoId  = $value['classInfoId'];
            $data['`classRoomName`'] = $value['classRoomName'];
            $data['`teacherName`'] = $value['teacherName'];
            $data['`className`'] = $value['className'];
            $data['`total`'] = $value['total'];


            $content.= "*************************************************************************************\r\n
\n";
            $content .= "老师名字:$teacherName  类型:$typeDetail  班级:$className  评价时间:$time";
            $content .=  "\r\n";

            $content1 = file_get_contents("http://127.0.0.1:$port_info/grade/download-$type_info?id=$classInfoId");

            $content.= $content1;

            $content .= "\r\n";

            $data['content'] = addslashes($content);

            $sql = insert($data,"estimate_history");

            $res = $remoteConn->query_num($sql);

            if($res == "1") {
                $this->db->query_num("update `estimate_history` set `post`='yes' where classInfoId="."'$classInfoId'");
            }


        $dataArray[] = $data;
            $sql="";
            $data="";
        }



    }

    public function download_file(){

        $dateNow = date("Ymd-His");
        $filename="$dateNow.txt";


        $content = $this->check_estimate_post_status();
        /*echo $content;exit;*/



        $encoded_filename = urlencode($filename);
        $filename = $encoded_filename = str_replace("+", "%20", $encoded_filename);


        header('application/force-download');

        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header("Content-Encoding: binary");
        header('Content-Disposition: attachment; filename="' .$filename. '"');
        header('Pragma: no-cache');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');


        $chars = $content; //需要导出的文件的内容

        echo $chars;

        exit();

    }

    public function substr_cut($str_cut,$length)
    {
        if (strlen($str_cut) > $length)
        {
            for($i=0; $i < $length; $i++)
                if (ord($str_cut[$i]) > 128)    $i++;
            $str_cut = substr($str_cut,0,$i)."..";
        }
        return $str_cut;
    }


}

?>
