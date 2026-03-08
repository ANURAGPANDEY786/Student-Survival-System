<?php
require_once "auth.php";
include("../db.php");

$id = intval($_GET["id"] ?? 0);
if($id<=0){ die("Invalid ID"); }

$rowRes = mysqli_query($conn, "SELECT * FROM food_places WHERE id=$id LIMIT 1");
$food = mysqli_fetch_assoc($rowRes);
if(!$food){ die("Not found"); }

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

    $photoName = $food["photo"];

    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK){
      $ext = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
      if(!in_array($ext, ["jpg","jpeg","png","webp"])){
        $msg = "Photo must be jpg/png/webp.";
      } else {
        // delete old
        if(!empty($photoName)){
          $old = "../../uploads/food/".$photoName;
          if(file_exists($old)) @unlink($old);
        }
        $photoName = "food_" . time() . "_" . rand(1000,9999) . "." . $ext;
        $targetDir = "../../uploads/food/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetDir . $photoName);
      }
    }

    if($msg===""){
      $stmt = $conn->prepare("UPDATE food_places SET name=?, type=?, price=?, distance=?, address=?, phone=?, photo=? WHERE id=?");
      $stmt->bind_param("ssidsisi", $name, $type, $price, $distance, $address, $phone, $photoName, $id);
      $ok = $stmt->execute();
      $stmt->close();

      if($ok){
        header("Location: food-manage.php");
        exit;
      } else $msg="Update failed.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Food Place</title>
  <style>
    body{ background:#0f0f0f; color:#fff; font-family:Arial; }
    .wrap{ max-width:800px; margin:30px auto; padding:0 12px; }
    .card{ background:#111; border:1px solid #222; border-radius:14px; padding:16px; }
    input,textarea{ width:100%; padding:10px; border-radius:10px; border:1px solid #222; background:#0b0b0b; color:#fff; margin-top:8px; box-sizing:border-box; }
    label{ display:block; margin-top:12px; font-weight:800; }
    .btn{ padding:10px 12px; border-radius:10px; border:none; cursor:pointer; font-weight:900; }
    .green{ background:#22c55e; color:#000; }
    .blue{ background:#3b82f6; color:#fff; text-decoration:none; display:inline-block; }
    img{ width:160px; height:110px; object-fit:cover; border-radius:12px; border:1px solid #222; margin-top:10px; }
    .err{ background:#2a1414; border:1px solid #ef4444; padding:10px; border-radius:10px; margin-bottom:10px; }
  </style>
</head>
<body>
<div class="wrap">
  <?php require_once "nav.php"; ?>

  <div class="card">
    <h2 style="margin:0;">✏ Edit Food Place</h2>

    <?php if($msg!==""){ echo '<div class="err">'.htmlspecialchars($msg).'</div>'; } ?>

    <form method="POST" enctype="multipart/form-data">
      <label>Name</label>
      <input name="name" value="<?php echo htmlspecialchars($food["name"]); ?>" required>

      <label>Type</label>
      <input name="type" value="<?php echo htmlspecialchars($food["type"]); ?>" required>

      <label>Price per meal</label>
      <input type="number" name="price" value="<?php echo intval($food["price"]); ?>" required>

      <label>Distance (km)</label>
      <input type="number" step="0.01" name="distance" value="<?php echo htmlspecialchars($food["distance"]); ?>" required>

      <label>Address</label>
      <textarea name="address" rows="3" required><?php echo htmlspecialchars($food["address"]); ?></textarea>

      <label>Phone</label>
      <input name="phone" value="<?php echo htmlspecialchars($food["phone"] ?? ""); ?>">

      <label>Replace Photo (optional)</label>
      <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
      <?php if(!empty($food["photo"])): ?>
        <div><img src="../../uploads/food/<?php echo htmlspecialchars($food["photo"]); ?>"></div>
      <?php endif; ?>

      <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
        <button class="btn green" type="submit">Update</button>
        <a class="btn blue" href="food-manage.php">Back</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>