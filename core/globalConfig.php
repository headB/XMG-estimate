<?php

//重要提示所有文件夹，最后的结尾都不需要加\或者/

//定义该程序的版本说明！！
define('program_version','btou_v1.5');
//一般全部变量（自定义变量）--配置信息--通用设定--setting都是以数组的形式表现数据啦
//2017-05-28更新新版本为1.5，新版的特性，增加了直接在这个配置文件配置发件人的信息填写
//2017-05-28增加了新功能，增加了学习反馈调查。
//一般全部变量（自定义变量）--配置信息--通用设定--setting都是以数组的形式表现数据啦


//举例define('emailSender',"775121173@qq.com");
define('emailSender',"lizhixuan@520it.com");
define('emailPasswd','lizhixuan123');


//下面这个是设定，用于展示给学生查看的index存放的文件夹（展示实时评价对象的链接）
//存放实时产生的评价对象链接index.html文件的保存文件夹位置：
define('iisShowDir',"/var/local/study_tomcat/webapps"); //缺省的位置是D盘

//定义服务器面向给学生的服务器IP地址（用于生成静态实时的html页面）
define('server_ip_address','192.168.113.1');


//注意，这里很重要，一定需要明确评价系统核心的评价程序node的确切位置（核心评价程序的文件夹名称为'TM2015',位于哪个文件夹）
//注意，定义的路径只需要在所在的文件夹，例如TM2015文件夹位于F:\wamp\XMG-estimate文件夹下面，只需要填这段就可以了
define('TM2015_path','/var/local/XMG-estimate');

//定义php.exe的具体位置！
//注意，需要填写绝对路径
//linux环境的话，这个项目是不需要填写的
define('php_exe_position','F:\wamp\bin\php\php5.5.12\php.exe');

//如果是linux环境的话，需要定义这个常量，node的安装位置。
define('node_exec_position','/var/local/node/bin');


//定义当前的站点信息，例如是当前的站点是F栋，就设置F。或者是SHENGDA-BLOCK-B
define('blockSite','F');

//定义系统类型
define('system_type','linux');

define('SLocation','12');
//现在就是想创建一个专业用于批量产生BAT文件的开关。
//这里的开关，就看账号时候设置了吧，如果设置不为空就定义为需要开启，不然的话就是不开启。

//这里添加一个说明，那就是linux系统和window系统的不同，需要用的脚本具体格式也是不一样的，
//好的，现在就这样区分，把具体要创建的命令（仅仅限于schtask命令）。
//设置一个大前提先，就是没有账号密码的话，下面这些定义都没有效果啦。！

//下面这两个变量一般用于生成批量的BAT文件，还有批量的任务计划，生成完成后一般马上去删除，以免账户泄露。


//准备去修复一下问题，自动定时更新的时候出问题。

?>
