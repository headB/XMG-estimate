<?php session_start();
	if(!isset($_SESSION['xz_username']))
		{header('location:login.php');exit;}
	 ?>
		<?php 
		if(!isset($title) or empty($title))
		{
			$title = "小码哥教学通用网络管理系统";
		}
 
 ?>
<?php 

if(isset($script))
{
	echo $script;
}

        /*<a href="renew.php"><span style="color:red">有问题点这里,但慎重</span></a>*/
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>

	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>行政部通用业务管理系统</title>

	<style type="text/css">
		p {align:center;font-size: 26px;}

		.dropdown-menu>li>a:hover, .dropdown-menu>li>a:focus, .dropdown-submenu:hover>a, .dropdown-submenu:focus>a {

			text-decoration: none;

			color: #ffffff;

			background-color: #0081c2;

			background-image: -moz-linear-gradient(top,  #0088cc,  #0077b3);

			background-image: -webkit-gradient(linear,  0 0,  0 100%,  from(#0088cc),  to(#0077b3));

			background-image: -webkit-linear-gradient(top,  #0088cc,  #0077b3);

			background-image: -o-linear-gradient(top,  #0088cc,  #0077b3);

			background-image: linear-gradient(to bottom,  #0088cc,  #0077b3);

			background-repeat: repeat-x;

			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc',  endColorstr='#ff0077b3',  GradientType=0);

		}



		.dropdown-menu>.active>a, .dropdown-menu>.active>a:hover, .dropdown-menu>.active>a:focus {

			color: #ffffff;

			text-decoration: none;

			outline: 0;

			background-color: #0081c2;

			background-image: -moz-linear-gradient(top,  #0088cc,  #0077b3);

			background-image: -webkit-gradient(linear,  0 0,  0 100%,  from(#0088cc),  to(#0077b3));

			background-image: -webkit-linear-gradient(top,  #0088cc,  #0077b3);

			background-image: -o-linear-gradient(top,  #0088cc,  #0077b3);

			background-image: linear-gradient(to bottom,  #0088cc,  #0077b3);

			background-repeat: repeat-x;

			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc',  endColorstr='#ff0077b3',  GradientType=0);

		}



		.dropdown-menu>.disabled>a, .dropdown-menu>.disabled>a:hover, .dropdown-menu>.disabled>a:focus {

			color: #999999;

		}



		.dropdown-menu>.disabled>a:hover, .dropdown-menu>.disabled>a:focus {

			text-decoration: none;

			background-color: transparent;

			background-image: none;

			filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);

			cursor: default;

		}



		.open {

			*z-index: 1000;

		}

		.open>.dropdown-menu {

			display: block;

		}



		.pull-right>.dropdown-menu {

			right: 0;

			left: auto;

		}



		.dropup .caret, .navbar-fixed-bottom .dropdown .caret {

			border-top: 0;

			border-bottom: 4px solid #000000;

			content: "";

		}



		.dropup .dropdown-menu, .navbar-fixed-bottom .dropdown .dropdown-menu {

			top: auto;

			bottom: 100%;

			margin-bottom: 1px;

		}



		.dropdown-submenu {

			position: relative;

		}



		.dropdown-submenu>.dropdown-menu {

			top: 0;

			left: 100%;

			margin-top: -6px;

			margin-left: -1px;

			-webkit-border-radius: 0 6px 6px 6px;

			-moz-border-radius: 0 6px 6px 6px;

			border-radius: 0 6px 6px 6px;

		}



		.dropdown-submenu:hover>.dropdown-menu {

			display: block;

		}



		.dropup .dropdown-submenu>.dropdown-menu {

			top: auto;

			bottom: 0;

			margin-top: 0;

			margin-bottom: -2px;

			-webkit-border-radius: 5px 5px 5px 0;

			-moz-border-radius: 5px 5px 5px 0;

			border-radius: 5px 5px 5px 0;

		}



		.dropdown-submenu>a:after {

			display: block;

			content: " ";

			float: right;

			width: 0;

			height: 0;

			border-color: transparent;

			border-style: solid;

			border-width: 5px 0 5px 5px;

			border-left-color: #cccccc;

			margin-top: 5px;

			margin-right: -10px;

		}



		.dropdown-submenu:hover>a:after {

			border-left-color: #ffffff;

		}



		.dropdown-submenu.pull-left {

			float: none;

		}

		.dropdown-submenu.pull-left>.dropdown-menu {

			left: -100%;

			margin-left: 10px;

			-webkit-border-radius: 6px 0 6px 6px;

			-moz-border-radius: 6px 0 6px 6px;

			border-radius: 6px 0 6px 6px;

		}

		.table th, .table td {
			text-align: center;
			height: 38px;
			font-size:18px;
		}



	</style>

	<link href="http://apps.bdimg.com/libs/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js"></script>
	<script src="http://apps.bdimg.com/libs/bootstrap/3.3.0/js/bootstrap.min.js"></script>

	<script language="javascript" type="text/javascript">

		function estimate_submit(){
			$("type='submit'").click(function(){$()})
		}

		function getArea(id,p,query){
			//初始化ajax
			var xhr = new XMLHttpRequest();
			var url = "ajax_estimate_type.php?"+query+"="+id+"&r="+Math.random()+"&xmg520it="+"Lizhixuan123!";
			var sel=document.getElementById(p);
			//var assetTable = document.getElementById('assetDetail');
			//assetTable.innerHTML='<tr><td>hello word</td></tr>';
			//打开请求
			xhr.open("get",url,true);
			//发送数据
			xhr.send(null);

			//等待响应
			xhr.onreadystatechange = function (){

				if(xhr.readyState == 4){
					var arr1=xhr.responseText.split(";");

					//清空下拉菜单
					sel.length=0;
					arr1.length = arr1.length-1;
					for(var i=0;i<arr1.length;i++){
						if(i==0){
							var xx = new Option('--请选择--','');
							sel.add(xx,null);
						}
						var arr2=arr1[i].split(":");

						//产生一个option对象
						//alert(arr2[0]);
						var opt=new Option(arr2[1],arr2[0]);
						//添加到当前列表当中
						sel.add(opt,null);

					}
				}
			};

		}


	</script>

</head>
<body>




<?php
if(!isset($title))

{echo "<center><p id='123'>--title 未被定义，所以输出默认标题-- </p></center>";}

else
{ echo "<center><p id='123'>$title </p></center>";} ?>
<center>
<div class="navbar navbar-default navbar-static-top" style="width:100%;">
	<nav  style="width: 1230px;margin:0 auto" role="navigation" >
		<div class="navbar-header">
			<a class="navbar-brand"  href="index.php">欢迎---<?php echo $_SESSION['xz_username'];?>---登陆</a>
		</div>
		<div>
			<ul class="nav navbar-nav">
				<li ><a href="prepare_setting.php">点击设置评价</a></li>
				<li class="dropdown">
					<a href="manageEstimating.php" class="dropdown-toggle" >
						管理当前评价
						<b class="caret"></b>
					</a>
				</li>
				<li class="dropdown">
					<a href="managerSql.php" class="dropdown-toggle" >
						评分数据导出
						<b class="caret"></b>
					</a>

				</li>

				<li class="dropdown">
					<a href="study.php" class="dropdown-toggle" >
						学习情况调查设置
						<b class="caret"></b>
					</a>
				</li>

				<li class="dropdown">
					<a href="renew.php" class="dropdown-toggle" >
						重置程序
						<b class="caret"></b>
					</a>
				</li>

				<li class="dropdown">
					<a href="network.php?m=index" class="dropdown-toggle" >
						网络管理
						<b class="caret"></b>
					</a>
				</li>

				<li class="dropdown">
					<a href="crawler.php" class="dropdown-toggle" >
						crawler
						<b class="caret"></b>
					</a>
				</li>


				<li ><a href="exit.php">退出</a></li>
			</ul>
		</div>
	</nav>

</div>
