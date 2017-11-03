<?php
$title= 'crawler';
include 'head-nav.php';
include 'view/tableView.php';
include 'function/function.php';

echo "<h1>千年蟲與化骨龍，大家好，我系張家輝</h1>";


$title = '';

$title[] = '机构名称';
$title[] = '网址';

$content[] = 'x';
$content[] = 'y';
$contentArray[] = $content;

listTable($title,$contentArray);

echo "<p>一下信息，均为测试，没有什么特殊意思！，尽量已经去除重复的！</p>";

$title='';
$content='';
$contentArray = '';


$title[] = '网站的标题';
$title[] = '网站的关键字描述';

$db->dataBase = 'crawl';

print_r($db);

$content[] = '';


echo "</center></body></html>";