<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM purchase_requests WHERE id='$id'");

header("Location: view_requests.php");
exit;
