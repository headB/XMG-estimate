<?php
header('content-type:text/html;charset=utf-8');
include 'function/function.php';
$queryPasswd = GET_values('xmg520it');
if($queryPasswd!='Lizhixuan123!')
{
    echo "error:error;";exit;
}

$block = GET_values('block');
$type = GET_values('type');

if(!empty($block)){
    $content = '';
    $res = $db_res->getValuesById($block,'classroom','blockNumber');
    echo $db_res->ajaxEstimateTypeQuery($res,'id','classNumber');
}

if(!empty($type)){
    $content= '';
    $res = $db_res->getValuesById($type,'port_type','tid');
    echo $db_res->ajaxEstimateTypeQuery($res,'id','type');
}

?>