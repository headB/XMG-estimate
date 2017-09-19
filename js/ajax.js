$(function(){

    //ajax方式加载第一级菜单 - 省份
    $.post("ajaxDepartment.php",{
        city:true
    },function(data,textStatus){

        //接收json数据
        var dataObj = eval("("+data+")"); //转换为json对象

        $.each(dataObj,function(idx,item){

            $option_new = $("<option value=\""+item.provinceID+"\">"+item.province+"</option>");
            $option_new.insertAfter($("#province").children(":first"));
        })

    })}