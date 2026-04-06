<?php
require_once "auth.php";
include("../db.php");

$id = $_GET["id"];

mysqli_query($conn,"DELETE FROM medical WHERE id=$id");

header("Location: medical-manage.php");