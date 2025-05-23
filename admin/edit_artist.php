<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM artist WHERE id='$id'");
$artist = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $bio = $_POST['bio'];

    mysqli_query($conn, "UPDATE artist SET name='$name', bio='$bio' WHERE id='$id'");

    echo "Artist updated! <a href='view_artists.php'>Back to list</a>";
    exit;
}
?>

<h2>Edit Artist</h2>
<form method="POST">
    Name: <input type="text" name="name" value="<?= $artist['name'] ?>" required><br><br>
    Bio:<br>
    <textarea name="bio" rows="4" cols="40"><?= $artist['bio'] ?></textarea><br><br>
    <button type="submit" name="submit">Update Artist</button>
</form>
