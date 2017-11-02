<?php
$title= 'crawler';
include 'head-nav.php';
include 'view/tableView.php';

echo "<h1>千年虫与化骨龙！</h1>";


$title = '';

$title[] = '机构名称';
$title[] = '网址';

$content[] = 'x';
$content[] = 'y';
$contentArray[] = $content;

listTable($title,$contentArray);


echo "</center></body></html>";