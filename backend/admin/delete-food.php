<?php
require_once "auth.php";
include("../db.php");

$id = intval($_GET["id"] ?? 0);
if($id<=0){ header("Location: food-manage.php"); exit; }

$res = mysqli_query($conn, "SELECT photo FROM food_places WHERE id=$id LIMIT 1");
$row = mysqli_fetch_assoc($res);

if($row && !empty($row["photo"])){
  $path = "../../uploads/food/".$row["photo"];
  if(file_exists($path)) @unlink($path);
}

mysqli_query($conn, "DELETE FROM food_places WHERE id=$id");
header("Location: food-manage.php");
exit;