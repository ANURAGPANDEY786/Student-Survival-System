<?php
session_start();
if (!isset($_SESSION["student_id"])) {
  header("Content-Type: application/json; charset=UTF-8");
  echo json_encode(["ok"=>false, "msg"=>"Login required"]);
  exit;
}

header("Content-Type: application/json; charset=UTF-8");
$conn = new mysqli("localhost","root","","sss");
if ($conn->connect_error) { echo json_encode(["ok"=>false]); exit; }

$pg_id = intval($_POST["pg_id"]);
$student = $_POST["student_name"];
$issue = $_POST["issue"];
$msg = $_POST["message"] ?? "";

$stmt = $conn->prepare("INSERT INTO pg_complaints (pg_id, student_name, issue, message) VALUES (?,?,?,?)");
$stmt->bind_param("isss", $pg_id, $student, $issue, $msg);
$stmt->execute();

echo json_encode(["ok"=>true]);
$stmt->close();
$conn->close();
