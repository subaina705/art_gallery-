<?php
session_start(); // Required to use session variables
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Check if the artist is associated with any artworks
    $check = mysqli_query($conn, "SELECT COUNT(*) AS total FROM artwork WHERE artist_id = $id");
    $data = mysqli_fetch_assoc($check);

    if ($data['total'] > 0) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Error: Cannot delete artist. They are associated with one or more artworks.'
        ];
    } else {
        mysqli_query($conn, "DELETE FROM artist WHERE id = $id");
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Artist deleted successfully.'
        ];
    }
} else {
    $_SESSION['alert'] = [
        'type' => 'warning',
        'message' => 'Invalid artist ID.'
    ];
}

header("Location: ../view-artist/view-artists.php");
exit;
?>
