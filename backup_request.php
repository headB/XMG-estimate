<?php
header('content-type:text/html;charset=utf-8');
include 'function/function.php';
$post_data['haha'] = 'haha';
echo response_url($post_data,"xmg520.com/xz/backup_operate.php");
?>