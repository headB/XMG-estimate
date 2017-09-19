<?php class crypt1 extends db{

    public function cryptStr($str){
        $key = "keyxuanB"; //密钥
        $cipher = MCRYPT_DES; //密码类型
        $modes = MCRYPT_MODE_ECB; //密码模式
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher,$modes),MCRYPT_RAND);//初始化向量

        $str_encrypt = mcrypt_encrypt($cipher,$key,$str,$modes,$iv); //加密函数

        /*$str_decrypt = mcrypt_decrypt($cipher,$key,$str_encrypt,$modes,$iv);*/
        //解密函数
        $str_encrypt = base64_encode($str_encrypt);

        return $str_encrypt;
    }

    public function decryptStr($str){
        $key = "keyxuanB"; //密钥
        $cipher = MCRYPT_DES; //密码类型
        $modes = MCRYPT_MODE_ECB; //密码模式
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher,$modes),MCRYPT_RAND);//初始化向量

        /*$str_encrypt = mcrypt_encrypt($cipher,$key,$str,$modes,$iv); */
        //加密函数


        $str = base64_decode($str);

        $str_decrypt = mcrypt_decrypt($cipher,$key,$str,$modes,$iv);
        //解密函数

        return $str_decrypt;
    }

    public function auth_send($url='',$post=''){
        if($url==''){$url = "http://www.xmg520.com/xz/sql_operate.php";}
        $post['womimashiduoshao'] = '^^^^kumanxuan^%%%^woshishuage!!';
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function auth_check(){

        if(!isset($_POST['womimashiduoshao']) or $_POST['womimashiduoshao']!="^^^^kumanxuan^%%%^woshishuage!!"){
            echo "error,auth";exit;
        }

    }



}

?>