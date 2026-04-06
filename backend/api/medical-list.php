<?php
header('Content-Type: application/json');
error_reporting(0);

include("../db.php");

// 📍 Default (college)
$lat = 26.8467;
$lng = 80.9462;

// 📍 Safe input
if(isset($_GET["lat"]) && isset($_GET["lng"])){
  $lat = floatval($_GET["lat"]);
  $lng = floatval($_GET["lng"]);
}

// 🔥 Query
$query = "
SELECT *,
(
  6371 * acos(
    cos(radians($lat)) *
    cos(radians(lat)) *
    cos(radians(lng) - radians($lng)) +
    sin(radians($lat)) *
    sin(radians(lat))
  )
) AS distance
FROM medical
ORDER BY distance ASC
";

$res = mysqli_query($conn, $query);

// ❌ If query fails → return JSON error
if(!$res){
  echo json_encode([
    "error" => true,
    "message" => mysqli_error($conn)
  ]);
  exit;
}

$data = [];

while($row = mysqli_fetch_assoc($res)){
  $data[] = $row;
}

// ✅ Always return valid JSON
echo json_encode($data);
exit;