<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript">
function validation()
{
    var name = document.getElementById("username").value;
    var pwd = document.getElementById("password").value;
    var postStr = "username="+name+"&password="+pwd;
    ajax("demo.php",postStr,function(result){
        document.getElementById("info").innerHTML=result;
    });
}

function ajax(url,postStr,onsuccess)
{
    var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP'); //创建XMLHTTP对象，考虑兼容性。XHR
    xmlhttp.open("POST", url, true); //“准备”向服务器的GetDate1.ashx发出Post请求（GET可能会有缓存问题）。这里还没有发出请求

    //AJAX是异步的，并不是等到服务器端返回才继续执行
    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState == 4) //readyState == 4 表示服务器返回完成数据了。之前可能会经历2（请求已发送，正在处理中）、3（响应中已有部分数据可用了，但是服务器还没有完成响应的生成）
        {
            if (xmlhttp.status == 200) //如果Http状态码为200则是成功
            {
                onsuccess(xmlhttp.responseText);
            }
            else
            {
                alert("AJAX服务器返回错误！");
            }
        }
    }
	xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    //不要以为if (xmlhttp.readyState == 4) {在send之前执行！！！！
    xmlhttp.send(postStr); //这时才开始发送请求。并不等于服务器端返回。请求发出去了，我不等！去监听onreadystatechange吧！
}
</script>
</head>

<body>

用户名:<input id="username" name="username" type="text"  />
<BR />
密码:<input id="password" name="password" type="password" />
<BR />
<input type="button" name="button" value="提交" onclick="validation();" />
<div id="info"></div>

</body>
</html>

