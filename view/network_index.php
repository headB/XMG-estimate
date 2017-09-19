
<script type="text/javascript">

    var InterValObj; //timer变量，控制时间
    var count = 10; //间隔函数，1秒执行
    var curCount;//当前剩余秒数
    var i = 1;

    function sendMessage() {

        var x=document.getElementById("loading");
        curCount = count;
        InterValObj = window.setInterval(SetRemainTime, 1500); //启动计时器，1秒执行一次

    }

    //timer处理函数
    function SetRemainTime() {
        if (curCount == 0) {
            window.clearInterval(InterValObj);//停止计时器
            i = 0;
            self.location='network.php?m=index';
        }
        else {
            var i1 = i*10;
            i++;
            curCount--;
            $("#loading").css("width",i1+"%");
        }
    }

    $(document).ready(function(){

    $('button').click(function(){
        var operate = this.id.split('+');
        $.get("network.php",{'m':'switch_operate','value':operate[0],'operate':operate[1]});
       $("#network").remove();
        $(".progress").css({"display":"block"});

        sendMessage();
        $("#testtwo").append("<b>指令正在执行ING,12.88秒后自动会刷新结果,或者可以点击操作其他页面<br>command已经收到你的order,正在执行ING。</b>");

    });

    });
</script>
<?php

$title='';
$contentArray='';

$title[]='具体课室';
$title[]='全部可以上网';
$title[]='设置定时上网';
$title[]='具体操作';
$title[]='具体操作';



foreach($network->classInfo as $value ){

        if (!empty($value['ACL'])) {


            $info1='';
            $info='';


            $info1 = $network->get_value_acl_rule($value['ACL']);

            if(empty($info1)){$info['allOnline']=$info['timerOnline']='';continue;}

            $class = $value['id'];
            if($info1['allOnline']=='yes'){


                $info['allOnline']="<span style='color:green' ><b>YES</b></span>";
                $info['allOperate']="<button id='$class+allOffline' class='btn btn-danger' >点击取消全部上网</button>";
            }
            else{
                $info['allOnline']="<span style='color:red' ><b>NO</b></span>";
                $info['allOperate']="<button id='$class+allOnline' class='btn btn-success' >点击启动全部上网</button>";
            }

            if($info1['timerOnline']=='yes'){
                $info['timerOnline']="<span style='color:green' ><b>YES</b></span>";
                $info['timerOperate']="<button id='$class+timerOff' class='btn btn-danger' >点击取消定时上网</button>";
            }
            else{
                $info['timerOnline']="<span style='color:red' ><b>NO</b></span>";
                $info['timerOperate']="<button id='$class+timerOn' class='btn btn-success' >点击启动定时上网</button>";
            }



            $content[] = $value['classNumber'];
            $content[] = $info['allOnline'];
            $content[] = $info['timerOnline'];
            $content[] = $info['allOperate'];
            $content[] = '';
            /*$content[] = $info['timerOperate'];*/
            $contentArray[] = $content;
            $content='';
    }
}

listTable($title,$contentArray);

echo "<br>";
echo "<p id='testtwo'></p>";

?>

<br><br>
<div class="progress"  style="width:50%;height: 80px;display:none">
    <div class="progress-bar" role="progressbar" aria-valuenow="60"
         aria-valuemin="0" aria-valuemax="100" id="loading" style="width: 0;">
    </div>
</div>

