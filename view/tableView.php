<?php

//首先说明一下，这里打算是设置一个用于增删改查的通用view模板，用于快速生成程序。
//那，要不要也设置一个controller呢，用于调用model嘛、

//这个是查的。资料的罗列
function listTable($list_title,$list_date)
{
    $i=1;
    echo<<<html
<table id="network" class="table table-responsive table-striped table-hover table-bordered" style="width:auto" cellspacing="0" cellpadding="0" border="1">
		 <tbody>
html;

    //现在的步骤应该是这样，一个标题，然后下面就是数据，数据的话，就是一个对应的标题值。
    //假如是每次都重复标题值的话，那就很多标题值啦，所以弄成两个数组，然后再合并数据吧。
    $title[] = "<td>序号</td>";

    foreach($list_title as $value)
    {
        if($temp = is_colspan($value))
        {
            $value = repla_colspan($value);
            $title[] = "<td $temp>".$value."</td>";
        }
        else
        {
            $title[] = "<td>".$value."</td>";
        }

    }

    foreach($list_date as $value)
    {

        if($temp1 = isSetIdType($value[0])){
            $content[] = "<tr class='hideTr'>";
            $value[0] = repla_IdType($value[0]);
        }
        else{
            $content[] = "<tr>";
        }

        $content[] = "<td>$i</td>";
        foreach($value as $value1)
        {
            if($temp = is_colspan($value1))
            {
                $value1 = repla_colspan($value1);
                $content[] = "<td $temp >".$value1."</td>";
            }
            else
            {
                $content[] = "<td>".$value1."</td>";
            }

        }
        $i++;
        $content[] = "</tr>";
    }

    echo "<tr>";
    foreach($title as $value)
    {
        echo $value;
    }
    echo "</tr>";


    foreach($content as $value)
    {
        echo $value;
    }


    echo "</tbody></table>";

}

function listTableNoSN($list_title,$list_date)
{
    $i=1;
    echo<<<html
<table class="table table-responsive table-striped table-hover table-bordered" style="width:auto" cellspacing="0" cellpadding="0" border="1">
		 <tbody>
html;

    //现在的步骤应该是这样，一个标题，然后下面就是数据，数据的话，就是一个对应的标题值。
    //假如是每次都重复标题值的话，那就很多标题值啦，所以弄成两个数组，然后再合并数据吧。


    foreach($list_title as $value)
    {
        if($temp = is_colspan($value))
        {
            $value = repla_colspan($value);
            $title[] = "<td $temp>".$value."</td>";
        }
        else
        {
            $title[] = "<td>".$value."</td>";
        }

    }

    foreach($list_date as $value)
    {
        $content[] = "<tr>";

        foreach($value as $value1)
        {
            if($temp = is_colspan($value1))
            {
                $value1 = repla_colspan($value1);
                $content[] = "<td $temp >".$value1."</td>";
            }
            else
            {
                $content[] = "<td>".$value1."</td>";
            }

        }
        $i++;
        $content[] = "</tr>";
    }

    echo "<tr>";
    foreach($title as $value)
    {
        echo $value;
    }
    echo "</tr>";


    foreach($content as $value)
    {
        echo $value;
    }


    echo "</tbody></table>";

}

//这里要设计一个用于提取colspan值用于输出的函数
//检测关键字，然后提取等于号(=)后面的数值。
function is_colspan($value){
    preg_match("#\scolspan='.'#",$value,$res);
    $res=!empty($res)?$res[0]:false;
    return $res;
}

function isSetIdType($value){
    preg_match("#\sclass='hideTr'#",$value,$res);
    $res=!empty($res)?$res[0]:false;
    return $res;
}

function repla_colspan($value){
    $res = preg_replace("#\scolspan='.'#",'',$value);
    return $res;

}

function repla_IdType($value){
    $res = preg_replace("#\sclass='hideTr'#",'',$value);
    return $res;

}

function createTableHead($EnterAIdForTableId){
    echo<<<html
    <p>固定资产小类明细</p>
<table class="table table-responsive table-striped table-hover table-bordered" style="width:auto" cellspacing="0" cellpadding="0" border="1">
		 <tbody id="$EnterAIdForTableId" >
		 </tbody></table>
html;
}

?>