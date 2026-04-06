<?php
require_once "auth.php";
include("../db.php");

if($_SERVER["REQUEST_METHOD"]=="POST"){
  $name = $_POST["name"];
  $type = $_POST["type"];
  $address = $_POST["address"];
  $phone = $_POST["phone"];
  $lat = $_POST["lat"];
  $lng = $_POST["lng"];
  $open24 = $_POST["open24"];

  mysqli_query($conn,"
    INSERT INTO medical(name,type,address,phone,lat,lng,open24)
    VALUES('$name','$type','$address','$phone','$lat','$lng','$open24')
  ");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Medical</title>
<style>
body{ background:#0f0f0f; color:#fff; font-family:Arial; }
.wrap{ max-width:600px; margin:30px auto; }
.card{ background:#111; padding:16px; border-radius:12px; border:1px solid #222; }

input,select{
  width:100%;
  padding:10px;
  margin-top:8px;
  border-radius:8px;
  border:1px solid #222;
  background:#000;
  color:#fff;
}

button{
  margin-top:12px;
  padding:10px;
  border:none;
  border-radius:10px;
  background:#22c55e;
  font-weight:bold;
}
</style>
</head>
<body>

<div class="wrap">
<?php require_once "nav.php"; ?>

<div class="card">
<h2>➕ Add Medical Facility</h2>

<form method="POST">

Name
<input name="name" required>

Type
<select name="type">
  <option value="hospital">Hospital</option>
  <option value="pharmacy">Pharmacy</option>
</select>

Address
<input name="address" required>

Phone
<input name="phone">

Latitude
<input name="lat" required>

Longitude
<input name="lng" required>

Open 24 Hours
<select name="open24">
  <option value="yes">Yes</option>
  <option value="no">No</option>
</select>

<button>Add Medical</button>

</form>
</div>
</div>

</body>
</html>