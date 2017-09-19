<?php
//下面写的这些参数差不多都是被用于分站的客户端判断使用，
//总的服务端只需要写一个curl转发请求夹带参数然后就等待被转发的请求返回一个反馈
//并且根据反馈信息来处理下一步，这些操作都是同步进行，就是一个成功以后才继续下一个操作
/*include 'function.php';*/

//下面函数是用户定义用户对“用户”功能的操作定义，以及过滤不必要的参数
/*function resolution_operate($opreate)
{
    if($opreate['action'] == 'user_add')
    {
        $opreate['action']='insert';
    }
    else
        if($opreate['action'] == 'user_update')
        {
            $opreate['action']='update';
        }
        else
            if($opreate['action'] == 'user_delete')
            {
                $opreate['action']='delete';
            }
            else
            {echo "当前没有接收到有效的操作提示，请检查你的动作参数";exit;}

    return $opreate['action'];

}*/

//这个就是走流程，看看究竟是用户增加，还是删除，还是修改，还有就是判断是否用户
//是否重复，重复的话就需要重新定义操作。
//还有一个很重要的问题，就是需要设置好哪些参数是传输，哪些是不必要的就unset或者重新定义一个数组
function exec_operate_only_for_form_client($postdata,$conn,$url)
{

    if($postdata['action']=='insert')
    {

        $res = resolution_user_repeat($postdata,$conn);
        preg_match('#^存在#', $res, $match1);

        preg_match('#^不存在#', $res, $match2);
        $res = "";
        print_r($match1);
        echo "<br>";
        print_r($match2);
        if (empty($match1))
        {

            //重新定义一个/一组数组参数，然后传递个给这个函数
            $list['uname'] = $postdata['uname'];
            $list['email'] = $postdata['email'];
            $list['mobile'] = $postdata['mobile'];
            $list['department'] = $postdata['department'];
            /*$table = 'user';
            $where['email'] = $list['email'];
            $sql = update($list,$table,$where);
            $res = $conn->query($sql);*/
            $res = syn_user_curl($list,"192.168.113.2/form/user_insert.php");
            preg_match('#成功#', $res, $match3);

            if(!empty($match3))
            {
                $result = "插入数据成功";
            }
            else
            {
                $result = "插入数据失败";
            }

            return $result;

        }
        if(empty($match2))
        {
            //重新定义一个/一组数组参数，然后传递个给这个函数
            $list['uname'] = $postdata['uname'];
            $list['email'] = $postdata['email'];
            $list['mobile'] = $postdata['mobile'];
            $list['department'] = $postdata['department'];
            /*$table = 'user';
            $sql = insert($list,$table);
            $res = $conn->query($sql);*/
            $res = syn_user_curl($list,"192.168.113.2/form/userUpdating.php");

            
            preg_match('#成功#', $res, $match3);

            if(!empty($match3))
            {
                $result = "修改数据成功";
            }
            else
            {
                $result = "修改数据失败";
            }

            return $result;
        }
    }




}

//数据库的插入，修改，删除，可以引用以前已经写好的参数，这里就引用
//function/function.php


//增加一个主服务端删除指定用户然后其他的客户端自动跟上的函数

function delete_user($postdata)
{
    if($postdata['action']!='delete')
    {
        echo "no action or wrong action";exit;
    }



}




//下面写的是关于postdata里面参数的检查，主要是检测是不是非空就可以
//不过也可以对传送的参数进行转换，保证程序内部安全
function filter_postdata($postdata)
{
    foreach ($postdata as $key => $post)
    {
        $postdata[$key] = addslashes($post);
    }
    return $postdata;
}

//



//下面函数是用于判断当前用户在各分站的信息是否存在
//不过具体的数据监测都是各客户端自己去监测，服务端这边只是等待一个响应来判断是否继续下一步的操作！！
function resolution_user_repeat($postdata,$conn)
{
    $name = $postdata['uname'];
    $res = $conn->query("select * from user where name='$name'");
    $num = $res->num_rows;
    if($num!="1")
    {$result = "不存在该用户！！";}
    else
    {$result = "存在该用户";}
    return $result;
}



?>