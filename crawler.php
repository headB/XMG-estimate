<?php
$title= 'crawler';
include 'head-nav.php';
include 'view/tableView.php';
include 'function/function.php';

echo "<h1>千年蟲與化骨龍，大家好，我系張家輝</h1>";

$db_res->dataBase = 'crawl';
$db_res->crawl();

//这里想弄一个变量，里面保存所有机构的id代码。
$companyInfo = $db_res->query("select * from site");



$title='';
$content='';
$contentArray = '';

$title[] = '机构名称';
$title[] = '网址';

foreach($companyInfo['values'] as $value){

    $content[] = $value['name'];
    $content[] = $value['url'];
    $contentArray[] = $content;
    $content='';

}




listTable($title,$contentArray);

echo "<p>以下信息，均为测试，没有什么特殊意思！，尽量已经去除重复的！</p>";

$title='';
$content='';
$contentArray = '';

$title[] = '归属';
$title[] = '网站的标题';
$title[] = '网站的关键字描述';


//忘记了，因为默认的数据库连接是xingzheng的，得写这条命令才可以执行连接新数据库






$tidInfoMark='';

foreach($companyInfo['values'] as $value){
    $id = $value['id'];
    $tidInfoMark[$id] = $value['name'];

}



$res = $db_res->query("select * from page");

foreach($res['values'] as $values){

    $id = $values['tid'];
    $content[] = $tidInfoMark[$id];
    $content[] = $values['title'];
    $content[] = $values['keyword'];
    $contentArray[] = $content;
    $content='';

}

echo "<div style='width:800px' >";
listTable($title,$contentArray);
echo "</div>";


echo "</center></body></html>";