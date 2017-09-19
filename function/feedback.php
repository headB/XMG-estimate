<?php
function feedback_template($optionArray,$selectArray=""){

    if(!is_array($optionArray)){echo "error,no optionArray gave in";exit;}

    $content = '[';

    $i=1;

    if(empty($selectArray)){
        $generalOption = <<<ECHO

"resume":"通用设置",
"answer":[
		{"text":"非常清楚","value":"A"},
		{"text":"基本清楚","value":"B"},
		{"text":"有点模糊","value":"C"},
		{"text":"几乎不懂","value":"D"}
	]}
ECHO;
    }
    else{
       $generalOption='';
    }





    foreach($optionArray as $key=>$value)
    {

        if($i!="1"){$content.=",";}

        $content .= "{ ";
        $content .= "\"content\":\"$value\",";
        $content .= $generalOption;

            $i++;
    }

    $content .= ' ]';


return $content;
}


