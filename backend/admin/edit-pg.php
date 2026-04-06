<?php
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) die("DB Error");

$id = intval($_GET["id"] ?? 0);
$res = $conn->query("SELECT * FROM pg_rooms WHERE id=$id");
$pg = $res ? $res->fetch_assoc() : null;
if(!$pg) die("PG not found");
?>
<?php require_once "auth.php"; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit PG</title>
  <style>
    body { background:#0f0f0f; color:white; font-family:Arial; }
    .box { max-width:600px; margin:40px auto; background:#111; padding:20px; border-radius:12px; }
    input, textarea, button { width:100%; margin-top:10px; padding:10px; border-radius:8px; border:none; }
    button { background:#facc15; font-weight:bold; cursor:pointer; }
    .small { opacity:0.8; font-size:13px; }
    img{ width:100%; max-height:220px; object-fit:cover; border-radius:12px; margin-top:10px; }
    a{ color:#3b82f6; text-decoration:none; }
  </style>
</head>
<body>
<div class="box">
  <?php require_once "nav.php"; ?>

  <h2>✏ Edit PG</h2>
  <p class="small"><a href="pg-manage.php">← Back to Manage</a></p>

  <?php if(!empty($pg["photo"])) { ?>
    <img src="../../uploads/pg/<?php echo htmlspecialchars($pg["photo"]); ?>" alt="photo">
  <?php } ?>

  <form action="./admin-uupdate-pg.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $pg["id"]; ?>">

    <input name="name" value="<?php echo htmlspecialchars($pg["name"]); ?>" placeholder="PG Name" required>
    <input name="price" type="number" value="<?php echo htmlspecialchars($pg["price"]); ?>" placeholder="Price" required>
    <input name="distance" value="<?php echo htmlspecialchars($pg["distance"]); ?>" placeholder="Distance (km)" required>
    <input name="type" value="<?php echo htmlspecialchars($pg["type"]); ?>" placeholder="PG / Room" required>
    <input name="phone" value="<?php echo htmlspecialchars($pg["phone"]); ?>" placeholder="Phone" required>
    <input name="address" value="<?php echo htmlspecialchars($pg["address"]); ?>" placeholder="Address" required>

    <textarea name="facilities" placeholder="Facilities"><?php echo htmlspecialchars($pg["facilities"]); ?></textarea>
    <textarea name="rules" placeholder="Rules"><?php echo htmlspecialchars($pg["rules"]); ?></textarea>

    <p class="small">Upload new photo only if you want to change it:</p>
    <input type="file" name="photo">

    <button>Update PG</button>
  </form>
</div>
</body>
</html>
<?php $conn->close(); ?>
