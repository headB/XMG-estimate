<?php 
        header("content-type:text/html;charset=utf-8");
        
        $DB_TBLName = "itcaststudent";
          
        $DB_DBName = "itcaststudent";   
        $class = $_POST['className'];
        $date = $_POST['date'];
        $savename = $class."_".$date;   
        
        include("conn.php");

        $file_type = "vnd.ms-excel";      
        $file_ending = "xls";  
        
        header("Content-Type: application/$file_type;charset=utf8");   
        header("Content-Disposition: attachment; filename=".$savename.".$file_ending");      
        //header("Pragma: no-cache");         
             
        $now_date = date("Y-m-j H:i:s");       
        $title = "你导出的班级：$class------导出调查表的调查时间是$date";       
             
        $sql = "Select * from $DB_TBLName where createDate ='$date' and className ='$class' ";       
        /*$ALT_Db = @mysql_select_db($DB_DBName, $conn) or die("Couldn't select database"); */
        $result = $conn1->query($sql) or die(mysql_error());
             
        echo("$title\n\n");       
        $sep = "\t";       
         echo "序号\t"."姓名\t"."班级\t"."不明白的地方\t"."你认为最近一周学习的内容\t"."你觉得最近一周学习的内容你吸收了：\t"."你最近一周每天下课之后花在学习的时间大概是\t"."你最近一周每天自己敲代码的时间大概有\t"."你最近一周每天会预习老师发的预习视频么\t"."调查的日期\t"."记录的IP地址\t";             
              
        print("\n");       
       $i = 1;       
        while($row = $result->fetch_row()) {
            $schema_insert = "$i\t";  
            for($j=0; $j< mysqli_num_fields($result);$j++) {
                if(!isset($row[$j]))       
                    $schema_insert .= "".$sep;       
                else if ($row[$j] != "")       
                    $schema_insert .= "$row[$j]".$sep;  
                else       
                    $schema_insert .= "".$sep;       
            }       
            $schema_insert = str_replace($sep."$", "", $schema_insert);       
            $schema_insert .= "\t";       
            print(trim($schema_insert));       
            print "\n";       
           $i++;       
        }       
        return (true);    
?>