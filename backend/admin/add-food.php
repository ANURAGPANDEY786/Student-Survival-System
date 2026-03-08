<?php
require_once "auth.php";
include("../db.php");

$msg = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){
  $name = trim($_POST["name"] ?? "");
  $type = trim($_POST["type"] ?? "");
  $price = intval($_POST["price"] ?? 0);
  $distance = floatval($_POST["distance"] ?? 0);
  $address = trim($_POST["address"] ?? "");
  $phone = trim($_POST["phone"] ?? "");

  if($name==="" || $type==="" || $price<=0 || $distance<=0 || $address===""){
    $msg = "Please fill all required fields.";
  } else {

    $photoName = null;
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK){
      $ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
      $safeExt = strtolower($ext);
      if(!in_array($safeExt, ["jpg","jpeg","png","webp"])){
        $msg = "Photo must be jpg/png/webp.";
      } else {
        $photoName = "food_" . time() . "_" . rand(1000,9999) . "." . $safeExt;
        $targetDir = "../../uploads/food/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetDir . $photoName);
      }
    }

    if($msg === ""){
      $stmt = $conn->prepare("INSERT INTO food_places(name,type,price,distance,address,phone,photo) VALUES(?,?,?,?,?,?,?)");
      $stmt->bind_param("ssidsis", $name, $type, $price, $distance, $address, $phone, $photoName);
      $ok = $stmt->execute();
      $stmt->close();

      if($ok){
        header("Location: food-manage.php");
        exit;
      } else {
        $msg = "DB insert failed.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Add Food Place</title>
  <style>
    body{ background:#0f0f0f; color:#fff; font-family:Arial; }
    .wrap{ max-width:800px; margin:30px auto; padding:0 12px; }
    .card{ background:#111; border:1px solid #222; border-radius:14px; padding:16px; }
    input,textarea,select{ width:100%; padding:10px; border-radius:10px; border:1px solid #222; background:#0b0b0b; color:#fff; margin-top:8px; box-sizing:border-box; }
    label{ display:block; margin-top:12px; font-weight:800; }
    .btn{ padding:10px 12px; border-radius:10px; border:none; cursor:pointer; font-weight:900; }
    .green{ background:#22c55e; color:#000; }
    .blue{ background:#3b82f6; color:#fff; text-decoration:none; display:inline-block; }
    .muted{ opacity:.85; font-size:13px; }
    .err{ background:#2a1414; border:1px solid #ef4444; padding:10px; border-radius:10px; margin-bottom:10px; }
  </style>
</head>
<body>
<div class="wrap">
  <?php require_once "nav.php"; ?>

  <div class="card">
    <h2 style="margin:0;">➕ Add Food Place</h2>
    <div class="muted">Fields marked required must be filled.</div>

    <?php if($msg!==""){ echo '<div class="err">'.htmlspecialchars($msg).'</div>'; } ?>

    <form method="POST" enctype="multipart/form-data">
      <label>Name (required)</label>
      <input name="name" required>

      <label>Type (required) (Mess/Tiffin/Canteen/Restaurant)</label>
      <input name="type" required>

      <label>Price per meal (required)</label>
      <input type="number" name="price" required>

      <label>Distance (km) (required)</label>
      <input type="number" step="0.01" name="distance" required>

      <label>Address (required)</label>
      <textarea name="address" rows="3" required></textarea>

      <label>Phone</label>
      <input name="phone">

      <label>Photo (jpg/png/webp)</label>
      <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">

      <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
        <button class="btn green" type="submit">Save</button>
        <a class="btn blue" href="food-manage.php">Back</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>