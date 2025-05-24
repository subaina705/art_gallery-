<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM artwork WHERE id = $id";
    mysqli_query($conn, $query);
}

header("Location: ../view-artworks/view-artworks.php");
exit;
