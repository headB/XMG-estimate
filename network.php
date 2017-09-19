<?php
include 'head-nav.php';
include 'function/function.php';
include 'function/crypt1.php';
include 'view/tableView.php';
$crypt = new crypt1();


include 'controller/networkController.php';


$network = new networkController();


$method = post_safe_html(GET_values('m'));
$method = addslashes($method);

$info = method_exists($network,$method);
if(!$info){
    echo "没有请求到任何的服务！";exit;
}

$network->$method();

if($method=='index'){

    include 'view/network_index.php';


}

