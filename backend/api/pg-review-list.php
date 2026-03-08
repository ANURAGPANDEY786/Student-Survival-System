<?php
header("Content-Type: application/json; charset=UTF-8");
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) { echo json_encode([]); exit; }

$pg_id = intval($_GET["pg_id"]);

// Reviews
$reviews = [];
$r = $conn->query("SELECT id, student_name, rating, review_text, created_at 
                   FROM pg_reviews 
                   WHERE pg_id=$pg_id 
                   ORDER BY created_at DESC 
                   LIMIT 10");
while ($row = $r->fetch_assoc()) $reviews[] = $row;

// Avg rating
$avgRes = $conn->query("SELECT AVG(rating) AS avg_rating, COUNT(*) AS total 
                        FROM pg_reviews 
                        WHERE pg_id=$pg_id");
$avgRow = $avgRes->fetch_assoc();
$avg_rating = $avgRow["avg_rating"] ? round((float)$avgRow["avg_rating"], 1) : 0;
$total = (int)$avgRow["total"];

// Top issues
$issues = [];
$i = $conn->query("
  SELECT issue, COUNT(*) as c 
  FROM pg_review_issues 
  WHERE review_id IN (SELECT id FROM pg_reviews WHERE pg_id=$pg_id)
  GROUP BY issue
  ORDER BY c DESC
  LIMIT 5
");
while ($row = $i->fetch_assoc()) $issues[] = $row;

echo json_encode([
  "avg_rating" => $avg_rating,
  "total_reviews" => $total,
  "top_issues" => $issues,
  "reviews" => $reviews
]);

$conn->close();
