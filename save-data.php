<?php 
require_once("database.php");
$obj = new database();

$sname = $_POST['sname'];
$sage = $_POST['sage'];
$scity = $_POST['scity'];

$value = ["name" => $sname, "age" =>$sage, "city" => $scity];


if($obj->insert("studentname", $value)) {
  $result = $obj->getresult();
  echo "<h1>Record Number ".$result[0]." Added.<br>Data is Inserted.</h1>";
}else {
  echo "<h1>Can not Inserted Data</h1>";
}
 ?>