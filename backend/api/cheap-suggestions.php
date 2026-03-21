<?php
session_start();
include("../db.php");

$user_id = $_SESSION["user_id"];

// get user rent spending
$res = mysqli_query($conn,"
SELECT SUM(amount) as rent 
FROM expenses 
WHERE user_id=$user_id AND category='rent'
");

$rent = mysqli_fetch_assoc($res)["rent"] ?? 0;

// suggest cheaper PG than current rent
$pg = mysqli_query($conn,"
SELECT name,price 
FROM pg_rooms
WHERE price < $rent 
ORDER BY price ASC 
LIMIT 1
");

$pgData = mysqli_fetch_assoc($pg);

// cheapest food
$food = mysqli_query($conn,"
SELECT name,price 
FROM food_places 
ORDER BY price ASC 
LIMIT 1
");

$foodData = mysqli_fetch_assoc($food);

echo json_encode([
  "pg"=>$pgData,
  "food"=>$foodData,
  "current_rent"=>$rent
]);