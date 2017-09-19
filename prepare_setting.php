<?php
include 'head-nav.php';
include 'function/function.php';
include 'view/tableView.php';
defined('blockSite') or define('blockSite','undefined');
defined('SLocation') or define('SLocation','');
$locationPlace = blockSite;

$x=SLocation;
if(empty($x)){echo "校区未定义，请联系网管!";exit();}

echo<<<Jqery
<script language="JavaScript" type="text/javascript">


    var InterValObj; //timer变量，控制时间
    var count = 12; //间隔函数，1秒执行
    var curCount;//当前剩余秒数
    var i = 1;

    function sendMessage() {

        var x=document.getElementById("loading");
        curCount = count;
        InterValObj = window.setInterval(SetRemainTime, 800); //启动计时器，1秒执行一次

    }


    //timer处理函数
    function SetRemainTime() {
        if (curCount == 0) {
            window.clearInterval(InterValObj);//停止计时器
            i = 0;
        }
        else {
            var i1 = i*10;
            i++;
            curCount--;
            $("#loading").css("width",i1+"%");
        }
    }

    $(document).ready(function(){
        //$('#bn100').click(function(){ $('#bn200').attr(disabled='disabled');});
        $("[type='submit']").click(function(){ $("[type='submit']").hide();sendMessage();
        });
    });
</script>
Jqery;

$db_res->recordLoginInformation();
//需要接收的数据有,port,location,user(检测权限).;'
//观察一下先，需要提交的参数有1.port,user_name,password,place(具体课室),total

/*$portTypeName = $db_res->getTypeNameById();*/

$area = $db_res->getValuesById(SLocation,'teachingarea','tid');
#print_r($area);
$areaArray = optionValues($area['values'],'block','--请选择--');

$subject = $db_res->getValuesById('0','subject_detail','tid');
$subjectArray = optionValues($subject['values'],'subjectName','--请选择--');


$estimate_type = $db_res->getValuesById('0','port_type','tid');
$estimate_typeArray = optionValues($estimate_type['values'],'type',"--请选择--");

$title = '';
$title[] = '评价所在区域';
$title[] = '班级所在课室';
$title[] = '评价类型';
$title[] = '评价类型细分类';

$content[] = optionTableAddContent('block',$areaArray,'',' onchange=" getArea(this.value,\'place\',\'block\') " ');
$content[] = optionTableAddContent('place','','',' id="place" ');
$content[] = optionTableAddContent('type',$estimate_typeArray," id='type' onchange=\" getArea(this.value,'typeDetail','type') \" ");
$content[] = optionTableAddContent('typeDetail','',''," id='typeDetail'  ");

$title1[] = '学科归属';
$title1[] = '被评价的老师名字';
$title1[] = '需要被评价的班级';
$title1[] = '该班级的参考人数';

$content1[] = optionTableAddContent('subject',$subjectArray,'',"");
$content1[] = commonTableAddContent('input','text','user_name','','请输入2-4个中文名字');
$content1[] = commonTableAddContent('input','text','password','','请输入班级名字');
$content1[] = commonTableAddContent('input','number','total','','请输入参考人数');


$contentArray[] = $content;
$contentArray[] = $title1;
$contentArray[] = $content1;

$locationPlace = $db_res->getValuesById($x,'teachingarea');


echo "<p>大家好，当前站点是".$locationPlace['values'][0]['block']."</p>";
echo "<p style='color:royalblue;font-size:30px;' >注意，这里有一个重要消息，新增JAVA小码哥讲师、辅导员评价!!请选择对应的评价！</p>";
echo "<br><form action='forwarder.php' method='post'>";
listTableNoSN($title,$contentArray);
echo "<br>";
echo "<input type='submit' value='点击提交'  class='btn btn-success' onclick=\"return confirm('确定提交评价申请?,提交后需要大概5-8秒钟时间开启评价')\"   >   ";
echo "</form>";

?>
<br><br>
    <div class="progress" style="width:50%;height: 80px">
        <div class="progress-bar" role="progressbar" aria-valuenow="60"
             aria-valuemin="0" aria-valuemax="100" id="loading" style="width: 0;">
        </div>
    </div>

<?php
echo "</center></body></html>";
?>
