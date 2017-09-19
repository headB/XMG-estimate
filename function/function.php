<?php

//加在function的话，可以顺便加载core里面的文件，假如有其他的php文件只是需要当独加载
//但是，function就相当依赖core里面的文件，为什么?因为里面涉及很多绝对路$径嘛。
include  dirname(__DIR__)."/core/globalConfig.php";
include_once  dirname(__DIR__)."/conn.php";
include dirname(__DIR__).DIRECTORY_SEPARATOR.'db.php';
$db_res = new db;

//这里设计一个用于检测当前系统类型（window还是linux）
function defineSystemType($systemTypeInfo=''){

	$sysType = (empty($systemTypeInfo))?PHP_OS:$systemTypeInfo;
	$sysType = strtolower($sysType);
	switch($sysType){
		case "linux":
			$sysType = 'linux';
			break;

		case "winnt":
			$sysType='winnt';
			break;

		case "window":
			$sysType = 'winnt';
			break;

		case "windows":
			$sysType = 'winnt';
			break;

		case "windows7":
			$sysType = 'winnt';
			break;

		case "windows8":
			$sysType = 'winnt';
			break;

		case "windows10":
			$sysType = 'winnt';
			break;

		case "windows2003":
			$sysType = 'winnt';
			break;

		case "windows2008":
			$sysType = 'winnt';
			break;

		case "windows2012":
			$sysType = 'winnt';
			break;

		case "":
			$sysType = '';
			break;

		default:
			$sysType = defineSystemType(system_type);
	}

	return $sysType;
}

$systemType = defineSystemType();
if(empty($systemType))
{
	echo "没有检测到系统类型,你可以尝试在globalConfig文件里面定义系统类型";exit;
}

include dirname(__DIR__).'/model/sysCmd.php';
include dirname(__DIR__).'/model/CMD'.$systemType.'.php';
/*include dirname(__DIR__).'/model/CMDlinux.php';
include dirname(__DIR__).'/model/CMDwinnt.php';*/
$objectName = "CMD".$systemType;
$sysCmd = new $objectName();


defined('username') or define('username','');
defined('password') or define('password','');
defined('tcp_ping') or define('tcp_ping','127.0.0.1');
if(username!='' and password!='')
{
	define('create_estimate_startPort_add_schtask','yes'); //
	define('create_timer_stop_estimate_schtask','no');  //
}

date_default_timezone_set('Asia/Shanghai');


//这个函数是，接收并查看当前端口是否已经开启，如果开启查看是否已经被占用，占用了就检测按序号到下一端口，循环操作
//注意，其实这里应该废除了查看当前端口是否被占用的情况了，我在自动10分钟定时杀程序的时候，设计成如果当前的node PID没有在数据库登记的话，
//一律格杀勿乱。那么，这个函数的作用就是

//下面这个函数等待删除
//below function is waitting for delete.


function path()
{
	defined('TM2015_path') or define('TM2015_path','');

	if(TM2015_path=='')
	{
		echo "错误！！,评价核心文件不存在或者路径出错！！";exit;
	}
	$res = preg_replace('#\\+$#','',TM2015_path);
	$res = preg_replace('#//+$#','',$res);
	$res = preg_replace('#\\+$#','',$res);
    $res = $res.DIRECTORY_SEPARATOR;
	return $res;

}

function curl_POST($post_data,$url){

	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://$url");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

	$output = curl_exec($ch);
	curl_close($ch);
	return $output;


}

function setting_estimate($port,$post_data)
{
  $ch=curl_init();
curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:$port/grade/init");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($post_data)));
$output = curl_exec($ch);
curl_close($ch);
return $output;
}

function detect_isset_estimate($port){

$post_data_t['test'] = "forTest";
$ch=curl_init();
curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:$port/grade/init");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_t);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
$output_test = curl_exec($ch);
curl_close($ch);
$output_test1 = htmlspecialchars_decode($output_test);
$output_test2 = "["."$output_test1"."]";
$output_test3 = json_decode($output_test2);
return $output_test3;
}
//estimate 需要注意动态相对路径
function estimate_stop($port)
{
	/*$port="";*/
	$post_data_t['what'] = "rr";
$ch=curl_init();
curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:$port/grade/commit");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_t);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
$output_test = curl_exec($ch);
curl_close($ch);
}

function stop_exec($word,$index="index")
{
	echo "<script>alert('$word');window.location.href='$index.php';</script>";exit;
}

//gradeConn 需要注意动态相对路径
function gradeConn($port)
{
	$obj = estimate_type_pos($port);
    $path = path();
	$db = new SQLite3($path."TM2015".DIRECTORY_SEPARATOR."db".DIRECTORY_SEPARATOR."grade-$obj.db");
	return $db;
}


function list_fetch($table,$where,$condition,$conn)
{
	$res = $conn->query("select * from $table where $where='$condition'");
	$res1 = $res->fetch_assoc();
	return $res1;
}

function estimate_type($port)
{
	include dirname(__DIR__)."/conn.php";
$port_type = substr($port,0,-1);
	$port_type.= '1';
	$estimate_type=list_fetch('port_type','port',$port_type,$conn);
	if(empty($estimate_type))
	{
		return "not defined";
	}
	 $estimate_type = $estimate_type['type'];

return $estimate_type;
}

function estimate_type_pos($port)
{
	include dirname(__DIR__).DIRECTORY_SEPARATOR."conn.php";
	$port_type = substr($port,0,-1)."1";
	$estimate_type=list_fetch('port_type','port',$port_type,$conn);
	if(empty($estimate_type))
	{
		return "not defined";
	}
	$estimate_type = $estimate_type['Rname'];

	return $estimate_type;

}

function estimate_type_port($port)
{
	$port_type = substr($port,0,-1)."1";
		return $port_type;

}

//gradeConn 需要注意动态相对路径


//创建BAT脚本然后运行，创建并且写入特点内容来启动评价程序，用于程序多开方面！！
//create_config 需要注意动态相对路径
//这里也是需要区分window端还是linux端


/*防火墙规则*/
/*netsh advfirewall firewall add rule */


function insert($list,$tableName)
{
$count=count($list);
$sql = "insert into $tableName";
$i=1;foreach ($list as $k => $v) {
	
	if($i==1 and $count==1)
		{$sql.="($k) ";}
		if($i==1 and $count!=1){$sql.="($k,";}
			if($i>1 and $i < $count){$sql.="$k,";}
				if($i>1 and $i==$count){$sql.="$k) ";}

	$i++;}


$i=1;foreach ($list as $k => $v) {
	
	if($i==1 and $count ==1 )
		{$sql.="values('$v')";}
		if($i==1 and $count!=1){$sql.="values('$v',";}
			if($i > 1 and $i < $count){$sql.="'$v',";}
				if($i>1 and $i==$count){$sql.="'$v') ";}

	$i++;}
	return $sql;
}

function update($list,$tableName,$where)
{
	$count = count($list);
	$sql = "update $tableName set ";
	$i = 1;
	foreach($list as $k => $v)
	{
		if($v=="null"){$sql.="$k = $v";}
		else
		{$sql.="$k = '$v'";}

		if($i<$count)
		{$sql.=",";}
		$i++;}



	$i=1; foreach ($where as $k => $v) {

	if($i==1)
	{$sql.=" where $k = '$v' ";}
	if($i>1)
	{$sql.="and $k = '$v' ";}
	$i++;

	$i++;}
	return $sql;}

function delete($tableName,$where)
{	$i = 1;
	$sql = "delete from $tableName ";
	foreach ($where as $k => $v) {
		if($i==1)
		{$sql.="where $k = '$v' ";}
		if($i>1)
		{$sql.="and $k = '$v' ";}
		$i++;}
	return $sql;
}

//2016-1-17 增加的文件

function post_safe_html($str){

	$html_string = array("&amp;", "&nbsp;", "'", '"', "<", ">", "\t", "\r");
	$html_clear = array("&", " ", "&#39;", "&quot;", "&lt;", "&gt;", "&nbsp; &nbsp; ", "");
	$js_string = array("/<script(.*)<\/script>/isU");
	$js_clear = array("");
	$frame_string = array("/<frame(.*)>/isU", "/<\/fram(.*)>/isU", "/<iframe(.*)>/isU", "/<\/ifram(.*)>/isU",);
	$frame_clear = array("", "", "", "");
	$style_string = array("/<style(.*)<\/style>/isU", "/<link(.*)>/isU", "/<\/link>/isU");
	$style_clear = array("", "", "");
	$str = trim($str);
	//过滤字符串
	$str = str_replace($html_string, $html_clear, $str);
	//过滤JS
	$str = preg_replace($js_string, $js_clear, $str);
	//过滤ifram
	$str = preg_replace($frame_string, $frame_clear, $str);
	//过滤style
	$str = preg_replace($style_string, $style_clear, $str);
	return $str;
}

function POST_filter($array){
	foreach($array as $key=>$value){
		$array[$key] = post_safe_html($value);
	}
	return $array;
}

$_POST = POST_filter($_POST);

function filter_postdata1($postdata)
{
	foreach ($postdata as $key => $post)
	{
		$postdata[$key] = addslashes($post);
	}
	return $postdata;
}

function syn_user_curl($post_data,$url)
{
	$ch=curl_init();
	$post_data['key_syn']= 'dsfzxcfgsdfdhsss#';
	curl_setopt($ch, CURLOPT_URL, "http://$url");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

function response_url($post_data,$url)
{
	$ch=curl_init();
	$post_data['syn_code']= 'xxx234245fKJHOIH234!2$##';
	curl_setopt($ch, CURLOPT_URL, "http://$url");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post_data));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

//2016-5-31 增加的函数
//这里自己的想法是，如果没有收到具体的端口号的话，就默认循环所有端口一遍。

//这个函数用于生成一个杀死所有node程序的配置文件

//2016-7-5号增加的！！
function issetV($array,$name,$defaultValue=''){
	$value = isset($array[$name])?$array[$name]:"$defaultValue";
	return $value;
}

function setContentLink($array,$name,$hrefLink,$target='_self'){
	if(empty($array))
	{
		$content = $name;
	}
	else{
		$content = issetV($array,$name);
	}

	if(!empty($content)){
		$content = "<a href='$hrefLink' target='$target'>".$content."</a>";
	}
	return $content;
}

function commonTableAddContent($tag,$type,$name,$value,$placeholder,$style='',$addCss='',$append=""){
	$value = !empty($value)?"value='$value'":"";
	$type = !empty($type)?"type='$type'":"";
	$placeholder = !empty($placeholder)?"placeholder='$placeholder'":"";
	$style = !empty($style)?"style='$style'":"";
	$addCss = !empty($addCss)?$addCss:"";
	$content=<<<html
	<$tag $type name="$name" $value $placeholder $style $addCss >$append
html;
	return $content;
}


function optionTableAddContent($name,$array,$style='',$addCss='',$selected='')
{

	$name = !empty($name)?$name:"";
	$array = is_array($array)?$array:"";
	$style = !empty($style)?"style='$style'":"";
	$addCss = !empty($addCss)?$addCss:"";
	if(!empty($array))
	{
		$option = "";
		if(!empty($selected))
		{
			foreach($array as $values)
			{
				$value = isset($values['value'])?$values['value']:"";
				if($selected==$value)
				{
					$selectedCss = "selected='selected'";
				}
				else
				{
					$selectedCss = "";
				}
				$content = isset($values['content'])?$values['content']:"";
				$option.= <<<html
		<option $selectedCss value="$value">$content</option>
html;
			}

		}
		else
		{
			foreach($array as $values)
			{
				$value = isset($values['value'])?$values['value']:"";
				$content = isset($values['content'])?$values['content']:"";
				$option.= <<<html
		<option  value="$value">$content</option>
html;
			}
		}

	}
	$option = isset($option)?$option:"";

	$content = <<<html
<select name="$name" $style $addCss >
$option
</select>
html;
	return $content;

}

function optionValues($select,$content,$placeholder=''){

	if(!empty($placeholder))
	{
		if(is_array($placeholder))
		{
			$id = $placeholder['value'];
			$contentTag = $placeholder['content'];
			$array = array('value'=>"$id",'content'=>"$contentTag");
			$selectArray[] = $array;
		}
		else{
			$value['value'] = 'nodata';
			$value['content'] = $placeholder;
			$selectArray[] = $value;
		}

	}

	foreach($select as $value1)
	{
		$value['value'] = isset($value1['id'])?$value1['id']:"";
		$value['content'] = isset($value1[$content])?$value1[$content]:"";
		$selectArray[] = $value;
	}
	return $selectArray;
}

function GET_values($name){
	$value = isset($_GET[$name])?$_GET[$name]:"";
	return $value;
}

function POST_values($name){
	$value = isset($_POST[$name])?$_POST[$name]:"";
	return $value;
}

function disabled($value){
	if(!empty($value)){
		return "disabled='disabled'";
	}
	else
	{
		return "";
	}
}

function val_by_id($array,$columnNameArray)
{
	foreach($columnNameArray as $value) {
		$val_array[$value] = issetV($array,$value);

	}
	return $val_array;
}

function today_or_buyDate($time){
	if(empty($time)){
		$time = date('Y-m-d',time());
	}
	return $time;
}

function sql_leftJoin_list($listArray,$tableName){
	$i=0;
	$sql = '';
	$count = count($listArray)-1;
	foreach($listArray as $value){
		if($i>=0 and $i!=$count){
			$sql.="$tableName.$value,";
		}
		if($i==$count)
		{
			$sql.="$tableName.$value";
		}
		$i++;
	}
	return $sql;
}

function test_passwd($username,$passwd,$jump_url){
	$cmd1 = "schtasks /create /TN pingjia-port /ST 10:00  /SC ONCE /TR cmd /RU $username /RP $passwd";
	exec($cmd1,$res1);
	$cmd2 = "schtasks /delete /TN pingjia-port /F";
	exec($cmd2,$res2);
	if(empty($res1))
	{
		stop_exec('账号或者密码错误',$jump_url);
	}
}

//2016-9.29-新增的文件！！
function throw_error_and_return($tip=''){
	$url = $_SERVER['HTTP_REFERER'];
	$tip = post_safe_html($tip);
	echo "<script>alert('$tip');window.location.href='$url';</script>";exit;
}

function Space()
{echo "<br>";}

?>