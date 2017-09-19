<?php
include 'head-nav.php';
include 'conn.php';
?>
<div style="margin: 0 auto; font-size: 18px;"><br>
<form action="classInsert.php" method="post" >
<table width="294" border="1" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="290"><strong style="color: #2096D8">输入需要创建的班级</strong></td>
    </tr>
    <tr>
      <td><input type="text" name="class" id="textfield"></td>
    </tr>
    <tr>
      <td><strong style="color: #2096D8">输入班主任名字（你的名字）</strong></td>
    </tr>
    <tr>
      <td><input type="text" name="classTeacher" id="textfield2"></td>
    </tr>
    <tr>
      <tr>
      <td><strong style="color: #2096D8">输入该班级毕业大概时间</strong></td>
    </tr>
    <tr>
      <td><input type="date" name="graduation" id="date"></td>
    </tr>
    <tr>
      <td align="center"><input type="submit" name="submit" id="submit" value="提交"></td>
    </tr>
  </tbody>
</table>


</form>
</div>
<?php
$res = $conn1->query('select * from classinfo');
if(!empty($res))
{
    echo "已经存在的班级有：↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓<br>";
    while($row = $res->fetch_assoc())
    {
        echo "----------".$row['name']."------------------<br>";
    }
}
?>

</body>
</html>
