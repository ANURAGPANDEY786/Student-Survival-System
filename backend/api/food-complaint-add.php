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
$issue = trim($_POST["issue"] ?? "");
$message = trim($_POST["message"] ?? "");

if($food_id<=0 || $issue==="" || $message===""){
  echo json_encode(["ok"=>false,"msg"=>"All fields required"]);
  exit;
}

$stmt = $conn->prepare("INSERT INTO food_complaints(food_id, student_id, issue, message, confirms, severity) VALUES(?,?,?,?,0,'Pending')");
$stmt->bind_param("iiss", $food_id, $student_id, $issue, $message);
$ok = $stmt->execute();
$stmt->close();

echo json_encode(["ok"=>$ok]);