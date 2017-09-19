<?php
class db
{
    public $query = NULL;
    public $dataBase = "xingzheng";

    public function __construct()
    {
        $this->query = self::conn();
    }

    public function conn()
    {
        $conn = new mysqli();
        $conn->connect('localhost', 'root', '', $this->dataBase);
        $conn->query('set names utf8');
        return $conn;
    }

    public function WiFi_conn(){

        $conn = new mysqli();
        $conn->connect('localhost', 'root', '', $this->dataBase);
        $conn->query('set names utf8');
        $this->query = $conn;
        return $conn;

    }

    public function estimate_conn(){
        $conn = new mysqli();
        $conn->connect('y.xmg520.cn', 'estimate', '', $this->dataBase,'3306');
        $conn->query('set names utf8');
        $this->query = $conn;
        return $conn;
    }

    public function WiFiQuery($sql){
        $conn = $this->WiFi_conn();

        $res = $conn->query($sql);
        $num = $conn->affected_rows;
        if ($num > 0) {
            while ($row = $res->fetch_assoc()) {
                $listtemp[] = $row;
            }
        }

        $list = (!empty($listtemp)) ? $listtemp : "";
        $date['values'] = $list;
        $date['num'] = $num;

        return $date;

    }


    public function query($sql)
    {
        $res = $this->query->query($sql);
        $num = $this->query->affected_rows;
        $listtemp = '';
        if ($num > 0) {
            while ($row = $res->fetch_assoc()) {
                $listtemp[] = $row;
            }
        }


        $list = (!empty($listtemp)) ? $listtemp : "";
        $date['values'] = $list;
        $date['num'] = $num;

        return $date;

    }

    public function query_num($sql)
    {

        $this->query->query($sql);
        $num = $this->query->affected_rows;
        return $num;
    }

    public function getPortTypeInfo($id)
    {
        $type_res = self::query("select * from port_type where id='$id'");
        $type_ress = $type_res['values'][0];
        $res = (!empty($type_ress)) ? $type_ress['Rname']: "no result return";
        return $res;
    }

    public function getRnamebyPortId($id)
    {
        $type_res = self::query("select * from port_type where id='$id'");
        $type_ress = $type_res;
        if(empty($type_ress['values'])){ echo "get Rname error";exit; }
        if(empty($type_ress['values'][0]['Rname'])){
            $tempRes = $this->getValuesById($type_ress['values'][0]['tid'],'port_type','id');
            if(empty($tempRes)) exit;
        $res = $tempRes['values'][0]['Rname'];
        }
        else{
            $res = $type_ress['values'][0]['Rname'];
        }
        return $res;
    }

    public function getFartherTypeRname($id)
    {
        $type_res = self::query("select * from port_type where id='$id'");
        $type_ress = $type_res;
        if(empty($type_ress['values'])){ echo "get Rname error";exit; }
        $tempRes = $type_ress['values'][0]['tid'];
        if($tempRes!='0'){
            $typeFartherRes = self::getValuesById($tempRes,'port_type','id');
            if(empty($typeFartherRes)) {echo "error,no farther Result";exit;}
            $res = $typeFartherRes['values']['0']['Rname'];
        }
        else{
            $res = $type_ress['values'][0]['tid'];
        }


        return $res;
    }


    public function getFartherPortById($id)
    {
        $type_res = self::query("select * from port_type where id='$id'");
        $type_ress = $type_res;
        if(empty($type_ress['values'])){ echo "get Father port error";exit; }
        $tempRes = $type_ress['values'][0]['tid'];
        if($tempRes!='0'){
            $typeFartherRes = self::getValuesById($tempRes,'port_type','id');
            if(empty($typeFartherRes)) {echo "get Father port error";exit;}
            $res = $typeFartherRes['values']['0']['port'];
        }
        else{
            $res = $type_ress['values'][0]['port'];
        }


        return $res;
    }


    public function getValuesByTable($tableName){
        $sql = "select * from $tableName";

        $res = self::query($sql);
        $res = (isset($res['values']) and !empty($res['values']))?$res:"";
        return $res;
    }

    public function getValuesById($id,$tableName,$where='id'){
        $sql = "select * from $tableName where $where ='$id'";
        $res = self::query($sql);
        $res = (isset($res['values']) and !empty($res['values']))?$res:"";
        return $res;
    }



    public function getTypeNameById($id,$tableName){
    $sql = "select * from $tableName where id='$id'";
        $res = self::query($sql);
        $res1 =  (isset($res['values'][0]) and !empty($res['values'][0]))?$res['values'][0]:"";
        return $res1;
}

    //this is specify for the Ajax use
    public function ajaxEstimateTypeQuery($resArray,$value='idColumn',$label='labelColumn'){
        $content = '';
        if(!empty($resArray['values'])){
            foreach($resArray['values'] as $value1){
                $content .= $value1[$value].":".$value1[$label].";";
            }

        }
        $res1 = (!empty($content))?$content:"";
        return $res1;
    }


    public function createNodeWwwConfig($estimateTypeId,$port){
        $port_type = self::getRnamebyPortId($estimateTypeId);
        $path = path()."TM2015";
        $fileContent = <<<html1
#!/usr/bin/env node
var debug = require('debug')('TM2014');


var fs = require("fs");
process.on("uncaughtException",function(err){
	fs.writeFile("$path/bin/nodejs_error_log.txt","err:"+err);
});

var app = require('../app-$port_type');
app.set('port', process.env.PORT || $port);

var server = app.listen(app.get('port'), function() {
  console.log("@启动成功");
  console.log("@打开浏览器输入：127.0.0.1: $port 进行使用");
});
html1;
       return $fileContent;
    }


    public function run_port_for_db(){
        $startPortArray = self::getValuesById('0','port_type','tid');
        if(empty($startPortArray)){echo "no data for init the estimate,check the type_port dataTable exist information";exit;}
        foreach($startPortArray['values'] as $value )
        {
            $port = $value['port'];
            $typeDetailId = $value['id'];
            $res = $this->checkIsSettingPort($port);
            if(!$res){
                $fileContent = self::createNodeWwwConfig($typeDetailId,$port);
                $file = path()."TM2015".DIRECTORY_SEPARATOR."bin".DIRECTORY_SEPARATOR."www-$port";
                file_put_contents($file,$fileContent);
                if(file_exists($file)){
                    $this->run_schtasks($port);
                }

            }


        }
    }

    public function recordLoginInformation(){
        $now = date('Y-m-d H:i:s',time());
        $remoteIp = $_SERVER['REMOTE_ADDR'];
        $username = $_SESSION['xz_username'];
        self::query_num("update admin set last_login_time='$now',last_login_ip='$remoteIp' where username='$username'");
    }

    public function checkTheEstimateRepeat($teacherName,$className){
        $sql = "select * from estimate where teacherName='$teacherName' and className='$className'";
        $num = self::query_num($sql);
        $res = ($num>0)?true:false;
        return $res;
    }

    public function getInitPort(){
        $res = $this->getValuesById('0','port_type','tid');
        $resArray = (!empty($res))?$res:"";
        if(empty($resArray)) {echo 'error,no init port Information';exit;}
        foreach($resArray['values'] as $value)
        {
            $portArray[] = $value['port'];
        }
        return $portArray;
    }

    public function AllFreeRuningEstimateInfo(){
        $portArray = self::getInitPort();
        foreach($portArray as $value){

            $i = (int)$value+1;
                for($i1=$i+7;$i1>=$i;$i1--){
                        $temp = $this->find_port_pid($i1);
                            if(!empty($temp)){
                                $sql_res = self::getValuesById($i1,'estimate','port');
                                    if(empty($sql_res)){$portArrayValues[] = $temp;}
                                            }
                                              }
                                        }
        if(!isset($portArrayValues) or empty($portArrayValues)){$portArrayValues='';}
       return $portArrayValues;
    }

    public function KillAllFreeRunningEstimate(){
        $portArrayValues = self::AllFreeRuningEstimateInfo();
        if(!empty($portArrayValues)){$this->kill_specify_nodeById($portArrayValues);$res='success';}
        else
        {
            $res = 'no node is running';
        }
        return $res;
    }

    public function escape($str){
        $str = $this->query->real_escape_string($str);
        return $str;
    }

    public function __destruct()
    {
        $this->query->close();
    }


}
?>