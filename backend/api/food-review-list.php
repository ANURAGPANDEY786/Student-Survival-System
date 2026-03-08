<?php
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

$food_id = intval($_GET["food_id"] ?? 0);
if ($food_id <= 0) { echo json_encode(["error"=>"Invalid food_id"]); exit; }

$metaQ = "
SELECT IFNULL(AVG(rating),0) as avg_rating, COUNT(id) as total_reviews
FROM food_reviews
WHERE food_id=$food_id
";
$metaR = mysqli_query($conn, $metaQ);
$meta = mysqli_fetch_assoc($metaR);

$listQ = "
SELECT fr.id, fr.rating, fr.review_text, fr.created_at, u.name as student_name
FROM food_reviews fr
LEFT JOIN users u ON u.id = fr.student_id
WHERE fr.food_id=$food_id
ORDER BY fr.id DESC
LIMIT 30
";
$listR = mysqli_query($conn, $listQ);

$reviews = [];
while($r = mysqli_fetch_assoc($listR)) $reviews[] = $r;

echo json_encode([
  "avg_rating" => round(floatval($meta["avg_rating"]), 1),
  "total_reviews" => intval($meta["total_reviews"]),
  "reviews" => $reviews
]);