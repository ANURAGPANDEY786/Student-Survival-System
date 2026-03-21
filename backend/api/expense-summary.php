<?php
session_start();
include("../db.php");

if(!isset($_SESSION["user_id"])) exit(json_encode([]));

$user_id = $_SESSION["user_id"];

// current month
$res = mysqli_query($conn,"
SELECT 
  SUM(amount) as total,
  category,
  COUNT(*) as count
FROM expenses 
WHERE user_id=$user_id 
AND MONTH(date)=MONTH(CURDATE())
GROUP BY category
");

$data = [];
$total = 0;

while($row = mysqli_fetch_assoc($res)){
  $data[] = $row;
  $total += $row["total"];
}

// last month
$res2 = mysqli_query($conn,"
SELECT SUM(amount) as last_total 
FROM expenses 
WHERE user_id=$user_id 
AND MONTH(date)=MONTH(CURDATE()-INTERVAL 1 MONTH)
");

$last = mysqli_fetch_assoc($res2)["last_total"] ?? 0;

echo json_encode([
  "categories"=>$data,
  "total"=>$total,
  "last_total"=>$last
]);