<?php

session_start();
if (!isset($_SESSION["student_id"])) {
  header("Content-Type: application/json; charset=UTF-8");
  echo json_encode(["ok"=>false, "msg"=>"Login required"]);
  exit;
}

header("Content-Type: application/json; charset=UTF-8");
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) { echo json_encode(["ok"=>false]); exit; }

$pg_id = intval($_POST["pg_id"]);
$student_name = $_POST["student_name"];
$rating = intval($_POST["rating"]);
$review_text = $_POST["review_text"] ?? "";
$issues = $_POST["issues"] ?? []; // array expected

// Insert review
$stmt = $conn->prepare("INSERT INTO pg_reviews (pg_id, student_name, rating, review_text) VALUES (?,?,?,?)");
$stmt->bind_param("isis", $pg_id, $student_name, $rating, $review_text);
$stmt->execute();
$review_id = $stmt->insert_id;
$stmt->close();

// Insert issues
if (is_array($issues)) {
  $stmt2 = $conn->prepare("INSERT INTO pg_review_issues (review_id, issue) VALUES (?,?)");
  foreach ($issues as $iss) {
    $stmt2->bind_param("is", $review_id, $iss);
    $stmt2->execute();
  }
  $stmt2->close();
}

echo json_encode(["ok"=>true, "review_id"=>$review_id]);
$conn->close();
