<?php
##列出当前时间对应的微信需要推送的内容
include 'function/function.php';

function syn_curl($url)
{
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, "$url");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
   # curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
    #curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    #$output = curl_exec($ch);
    curl_close($ch);
    #return $output;
}

$time = date("H:i:00",time());

$infoArray  = $db_res->getValuesByTable('timer_send');

foreach($infoArray['values'] as $value ){

    $time1 = $value['sendTime'];

    if ($time == $time1){
        $content = urlencode($value['content']);
        $content1 = urlencode("行政部伙伴群");

            $cmd = "192.168.113.10:82/sendmsg?username=lykchat&pwd=123456&friendfield=0&friend=$content1&content=$content";

        # syn_curl($cmd);
        $data['tt'] = 'dd';
        syn_user_curl($data,$cmd);

    }

}