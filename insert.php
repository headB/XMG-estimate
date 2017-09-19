<?php

$conn = new mysqli();
$conn->connect('localhost','test1','lizhixuan123!','xingzheng');
$conn->query('set names utf8');

$conn1 = new mysqli();
$conn1->connect('localhost','root','kumanxuan@gzitcast','xingzheng1');
$conn1->query('set names utf8');


$res = $conn1->query('select * from user');
while($row = $res->fetch_assoc())
{   $department = $row['department'];
    $name = $row['name'];
    $conn->query("update user set department='$department' where name='$name'");
    $conn->affected_rows;
}

$res = $conn->query("select * from user");
while($row = $res->fetch_assoc())
{
    echo $row['department']."<br>";
}