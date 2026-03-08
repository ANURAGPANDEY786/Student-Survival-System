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

$complaint_id = intval($_POST["complaint_id"]);
$student = $_POST["student_name"];

// prevent duplicate confirm by same student
$check = $conn->prepare("SELECT id FROM pg_complaint_confirms WHERE complaint_id=? AND student_name=?");
$check->bind_param("is", $complaint_id, $student);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  echo json_encode(["ok"=>false, "msg"=>"Already confirmed"]);
  $check->close();
  $conn->close();
  exit;
}
$check->close();

$stmt = $conn->prepare("INSERT INTO pg_complaint_confirms (complaint_id, student_name) VALUES (?,?)");
$stmt->bind_param("is", $complaint_id, $student);
$stmt->execute();

echo json_encode(["ok"=>true]);
$stmt->close();
$conn->close();
