<?php
header('content-type:text/html;charset=utf-8');


include 'function/function.php';
include 'function/crypt1.php';
$secret = new crypt1();
$content = $secret->auth_send();


if(strlen($content)<=10){
    echo $content;
}



$SQLarray = explode("+-+",$content);


if(empty($SQLarray[0])){echo "error,no data!!";exit;}


//下面循环列出所有有组合的SQL语句
foreach($SQLarray as $key=>$value_id){



    if(!empty($value_id)){ $idArray = explode('-id-',$value_id);}else{/*echo "nodata!!";*/exit;}

    $blockInfo[blockSite] ='';

    if(empty($idArray[1])){/*echo "error";*/exit;}

    $idValue = explode(';',$idArray[0]);

    if(!empty($value_id)){

        for($i=1;$i<=5;$i++){

            if($i==1){$blockInfo['D'] = $idValue[$i];}
            if($i==2){$blockInfo['F'] = $idValue[$i];}
            if($i==3){$blockInfo['bigPlace'] = $idValue[$i];}
            if($i==4){$blockInfo['beijing'] = $idValue[$i];}
                if($i==5){
                    if(!empty($idValue[$i])){
                        $db_res->dataBase = $idValue[$i];
                        $db_res->WiFi_conn();
                    }else{
                        $db_res->dataBase = "xingzheng";
                        $db_res->WiFi_conn();
                    }

                }


        }



    }

    if($blockInfo[blockSite]=='yes'){
        /*echo "没有需要执行的命令";*/goto next;
    }



    $i = 1;
    $condition_value = false;
    $execute_condition = false;

        $array1 = explode("+_", $idArray[1]);
        foreach ($array1 as $value2) {


            $t = htmlspecialchars($secret->decryptStr($value2));
            if (!empty($t)) {



                $tArray = explode('php+', $t);
                foreach ($tArray as $value3) {

                    if ($i == 1) {
                        $res = $db_res->query_num(trim($value3));

                        if ($res > 0) {

                            $condition_value = true;
                        }
                    }

                    if ($i == 2 and $condition_value == true) {
                        $t1 = trim($value3);
                        $res = $db_res->query_num($t1);
                        echo "$t1<br>";
                        /*echo "this is query_num-->".$res."<br>";*/
                        if ($res >= 0) {
                            /*echo "更新执行成功！！";*/
                            $execute_condition = true;
                        } else {
                           /* echo "更新执行失败";*/
                        }
                    }

                    if ($i == 3 and $condition_value == false) {
                        $t = trim($value3);
                        $res = $db_res->query_num($t);
                        echo "$t<br>";
                        if ($res > 0) {
                            /*echo "插入执行成功！！";*/
                            $execute_condition = true;
                        } else {
                            /*echo "插入执行失败";*/
                        }
                    }
                }
                $i++;
            }
        }



    if ($execute_condition == true) {
        $postData['blockSite'] = blockSite;
        $postData['id'] = $idValue[0];

        $content = $secret->auth_send("http://www.xmg520.com/xz/common_response.php", $postData);
        echo $content;

        /*echo "<br>";
        echo $content;*/
    }
    next:
}



?>