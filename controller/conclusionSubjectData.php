<?php class conclusionSubjectData{

    public $db = NULL;

    public function __construct()
    {

        $this->db = new db();
    }

    //这里想设计一个功能，被调用的时候，自动去estimate_history,自动把每一条对应的评价记录对应的学院，把被评价的名字等信息汇总到一个指定的数据表
    public function collectSubjectTeacherName(){

        $allSubjectInfo = $this->db->getValuesById('0','subject_detail','tid');
        foreach($allSubjectInfo['values'] as $value ){

            $teacherInfo = $this->db->getValuesById($value['id'],'estimate_history','sid');
            foreach($teacherInfo['values'] as $value1 ){

                $teacherName = $value1['teacherName'];
                $teacherInfo1[] = array('teacherName'=> "$teacherName");

            }

            $test = $this->trimRepeatValues($teacherInfo1,'teacherName');

            $this->insertDataCheck($test,$value['id']);


            echo "<br><br>这里显示的是".$value['subjectName']."学科的所有讲师名字";
            print_r($test);
            echo "<br>";
            $teacherInfo1 = '';
            $test='';


        }



    }


    public function trimRepeatValues($arrayValues,$fieldName){
        $fieldName = $this->db->escape($fieldName);

        $repeatObj = '';

       foreach($arrayValues as $value){
           $repeatObj[] = $value[$fieldName];
       }

        $trimRepeatValues = array_unique($repeatObj);

        return $trimRepeatValues;

    }

    public function insertDataCheck($arrayData,$sid){

        foreach($arrayData as  $value ){

            $value = $this->db->escape($value);

            $objInfo = $this->db->query_num("select * from subject_detail where `subjectTeacherName`='$value' and `tid`='$sid'");
            echo $objInfo;
            if($objInfo > 0 ){
            /*$list['subjectTeacherName'] = $value;
                $list['sid'] = $sid;
                $updateSql = update($list,'subject_detail',)*/
            }

            if($objInfo == 0 ){

                $list['subjectTeacherName'] = $value;
                $list['tid'] = $sid;
                $insertSql = insert($list,'subject_detail');
                $this->db->query_num($insertSql);

            }


        }

    }

    //调用这个函数，会显示导出所有评价结果的首页
    public function index(){



    }

}

?>