<?php
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

/* Get sorting */
$sort = $_GET["sort"] ?? "nearest";

/* Fetch food places */
$query = "
SELECT f.*,
IFNULL(AVG(r.rating),0) as avg_rating,
COUNT(r.id) as total_reviews
FROM food_places f
LEFT JOIN food_reviews r ON f.id = r.food_id
GROUP BY f.id
";

$result = mysqli_query($conn, $query);

$foods = [];

while($row = mysqli_fetch_assoc($result)){

    /* Calculate Risk */
    $riskQuery = "
    SELECT SUM(confirms) as total_confirms
    FROM food_complaints
    WHERE food_id = ".$row["id"]."
    ";
    $riskRes = mysqli_query($conn, $riskQuery);
    $riskRow = mysqli_fetch_assoc($riskRes);
    $confirms = $riskRow["total_confirms"] ?? 0;

    if($confirms >= 10){
        $risk = "High Risk";
    } elseif($confirms >= 5){
        $risk = "Warning";
    } else {
        $risk = "Safe";
    }

    $row["risk"] = $risk;
    $foods[] = $row;
}

/* Sorting */
if($sort === "cheapest"){
    usort($foods, fn($a,$b)=> $a["price"] <=> $b["price"]);
}
elseif($sort === "top"){
    usort($foods, fn($a,$b)=> $b["avg_rating"] <=> $a["avg_rating"]);
}
else{
    usort($foods, fn($a,$b)=> $a["distance"] <=> $b["distance"]);
}

echo json_encode($foods);