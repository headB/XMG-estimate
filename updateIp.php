<?php

header ('content-type:text/html;charset=utf-8');



function syn_curl($url,$post_data)

{

    $ch=curl_init();

    $post_data['key_syn']= 'ttyderfre4e345wer345wer34ee';

    curl_setopt($ch, CURLOPT_URL, "http://$url");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

    $output = curl_exec($ch);

    curl_close($ch);

    return $output;

}



$post_data['location'] = 'f栋';

$post_data['domain'] = 'f.xmg520.cn';

echo syn_curl('xmg520.cn/xz/iprecord.php',$post_data);









?>
