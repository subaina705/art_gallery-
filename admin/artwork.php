<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$result = mysqli_query($conn, "SELECT * FROM artist");

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $artist_id = $_POST['artist_id'];
    $description = $_POST['description'];

    $query = "INSERT INTO artwork (title, artist_id, description) 
              VALUES ('$title', '$artist_id', '$description')";
    mysqli_query($conn, $query);
    echo "Artwork added successfully!";
}
?>

<h2>Add Artwork</h2>
<form method="POST">
    <input type="text" name="title" placeholder="Artwork Title" required><br><br>
    
    <label>Artist:</label>
    <select name="artist_id" required>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>
    
    <textarea name="description" placeholder="Description" required></textarea><br><br>
    
    <button type="submit" name="submit">Add Artwork</button>
</form>
