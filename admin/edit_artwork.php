<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$id = $_GET['id'];
$get_artwork = mysqli_query($conn, "SELECT * FROM artwork WHERE id = $id");
$artwork = mysqli_fetch_assoc($get_artwork);

$artists = mysqli_query($conn, "SELECT * FROM artist");

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $artist_id = $_POST['artist_id'];
    $desc = $_POST['description'];

    mysqli_query($conn, "UPDATE artwork 
                         SET title='$title', artist_id='$artist_id', description='$desc' 
                         WHERE id = $id");

    header("Location: view_artworks.php");
    exit;
}
?>

<h2>Edit Artwork</h2>
<form method="POST">
    <input type="text" name="title" value="<?= $artwork['title'] ?>" required><br><br>
    
    <label>Artist:</label>
    <select name="artist_id" required>
        <?php while($a = mysqli_fetch_assoc($artists)): ?>
            <option value="<?= $a['id'] ?>" <?= ($a['id'] == $artwork['artist_id']) ? 'selected' : '' ?>>
                <?= $a['name'] ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <textarea name="description" required><?= $artwork['description'] ?></textarea><br><br>
    
    <button type="submit" name="update">Update</button>
</form>
