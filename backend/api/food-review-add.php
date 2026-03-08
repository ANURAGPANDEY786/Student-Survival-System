<?php
session_start();
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

if(!isset($_SESSION["student_id"])){
  echo json_encode(["ok"=>false,"msg"=>"Login required"]);
  exit;
}

$student_id = intval($_SESSION["student_id"]);
$food_id = intval($_POST["food_id"] ?? 0);
$rating = intval($_POST["rating"] ?? 0);
$review_text = trim($_POST["review_text"] ?? "");

if($food_id<=0 || $rating<1 || $rating>5){
  echo json_encode(["ok"=>false,"msg"=>"Invalid data"]);
  exit;
}

/* Optional: prevent spam (one review per student per food) */
$chk = mysqli_query($conn, "SELECT id FROM food_reviews WHERE food_id=$food_id AND student_id=$student_id LIMIT 1");
if(mysqli_num_rows($chk) > 0){
  echo json_encode(["ok"=>false,"msg"=>"You already reviewed this place"]);
  exit;
}

$stmt = $conn->prepare("INSERT INTO food_reviews(food_id, student_id, rating, review_text) VALUES(?,?,?,?)");
$stmt->bind_param("iiis", $food_id, $student_id, $rating, $review_text);
$ok = $stmt->execute();
$stmt->close();

echo json_encode(["ok"=>$ok]);