<?php
session_start();

/* Prevent caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION["student_id"])) {
  echo json_encode(["loggedIn" => false]);
  exit;
}

echo json_encode([
  "loggedIn" => true,
  "student_id" => $_SESSION["student_id"],
  "student_name" => $_SESSION["student_name"] ?? ""
]);
