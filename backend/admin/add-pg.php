<?php require_once "auth.php"; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Add PG / Room</title>
  <style>
    body { background:#0f0f0f; color:white; font-family:Arial; }
    .box {
      max-width: 500px;
      margin: 40px auto;
      background:#1a1a1a;
      padding:20px;
      border-radius:12px;
    }
    input, textarea, button {
      width:100%;
      margin-top:10px;
      padding:10px;
      border-radius:6px;
      border:none;
    }
    button {
      background:#22c55e;
      font-weight:bold;
      cursor:pointer;
    }
  </style>
</head>

<body>
<div class="box">
  <?php require_once "nav.php"; ?>

  <h2>Add New PG / Room</h2>

  <form action="../api/add-pg.php" method="POST" enctype="multipart/form-data">

    <input name="name" placeholder="PG Name" required>
    <input name="price" type="number" placeholder="Price" required>
    <input name="distance" placeholder="Distance (km)" required>
    <input name="type" placeholder="PG / Room" required>
    <input name="phone" placeholder="Phone" required>
    <input name="address" placeholder="Address" required>

    <textarea name="facilities" placeholder="Facilities"></textarea>
    <textarea name="rules" placeholder="Rules"></textarea>

    <input type="file" name="photo" required>

    <button>Add PG</button>
  </form>
</div>
</body>
</html>
