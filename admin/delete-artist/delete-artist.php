<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM artist WHERE id='$id'");

header("Location: ../view-artist/view-artists.php");
exit;
