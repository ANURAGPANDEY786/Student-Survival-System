<?php
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

$id = intval($_GET["id"] ?? 0);
if ($id <= 0) { echo json_encode(["error"=>"Invalid ID"]); exit; }

$q = "
SELECT f.*,
IFNULL(AVG(r.rating),0) as avg_rating,
COUNT(r.id) as total_reviews
FROM food_places f
LEFT JOIN food_reviews r ON f.id = r.food_id
WHERE f.id = $id
GROUP BY f.id
";
$res = mysqli_query($conn, $q);
$row = mysqli_fetch_assoc($res);

if(!$row){ echo json_encode(["error"=>"Not found"]); exit; }

/* Risk = based on total confirms (sum) on complaints */
$qr = "SELECT IFNULL(SUM(confirms),0) as total_confirms FROM food_complaints WHERE food_id=$id";
$rr = mysqli_query($conn, $qr);
$rrow = mysqli_fetch_assoc($rr);
$confirms = intval($rrow["total_confirms"] ?? 0);

if($confirms >= 10) $risk = "High Risk";
elseif($confirms >= 5) $risk = "Warning";
else $risk = "Safe";

$row["risk"] = $risk;
$row["total_confirms"] = $confirms;

echo json_encode($row);