<?php 
require_once('database.php');
$obj = new database();

// Insert Data in Database
// $obj->insert('personal',['name' =>'ali','age'=>19,'city'=>'ali khan']);
// echo "Insert Result is :";
// print_r($obj->getResult());

// Update Date in Database
// $obj->update('personal',['name' =>'ali','age'=>19,'city'=>'ali khan'],'age="20"');
// echo "Update Result is :";
// print_r($obj->getResult());

// Delete Data in Database
// $obj->delete('personal');
// echo "Delete Result is :";
// print_r($obj->getResult());

// Fetch Raw Data from database
// $obj->sql('SELECT * FROM personal where id = 35');
// echo "Sql Result is :";
// echo '<pre>';
// print_r($obj->getResult());
// echo '</pre>';

// Fetch Conditional Data from database

// $table = "studentname";
// $join = "right join city ON studentname.city = city.cid";
// $colName = "studentname.id, studentname.name, studentname.age,city.cityname";
// $limit = 2;
// $obj->select("$table", "$colName", $join, null, null, "$limit");
// echo "Select Result is :";
// echo '<pre>';
// print_r($obj->getResult());
// echo '</pre>';

// Pagination from database
// $obj->pagination("$table", $join, null, "$limit");
// echo "pagination Result is :";
// echo '<pre>';
// print_r($obj->getResult());
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP OOP</title>
  <link rel="stylesheet" href="./dist/style.css">
</head>
<body>
  <form action="save-data.php" method="POST">
    <label for="name">Name</label>
    <input type="text" name="sname"><br><br>
    <label for="age">age</label>
    <input type="text" name="sage"><br><br>
    <label for="city">City</label>
    <select name="scity" id="">
      <?php 
      $obj->select("city");
      $result = $obj->getResult();
      foreach ($result as list("cid"=>$cid, "cityname" => $cname)) {
        echo "<option value='$cid'>$cname</option>";
      }
      ?>
    </select>
    <br>
    <br>
    <input type="submit" value="save">
  </form>
  <?php 
  $table = "studentname";
  $join = "left join city ON studentname.city = city.cid";
  $colName = "studentname.id, studentname.name, studentname.age,city.cityname";
  $limit = 5;
  $obj->select("$table", "$colName", $join, null, null, "$limit");
  
  $result = $obj->getResult();

echo "<table border='1' width='500px'>";
foreach ($result as list("id" => $id, "name" => $name, "age"=> $age, "cityname" => $city)) {
  echo "<tr><td>$id</td><td>$name</td><td>$age</td><td>$city</td></tr>";
}
echo "</table>";

// Pagination from database
$obj->pagination("$table", $join, null, "$limit");

  ?>
</body>
</html>