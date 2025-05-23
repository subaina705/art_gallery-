<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

// Fetch categories for dropdown
$categories = mysqli_query($conn, "SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $artist_id = $_POST['artist_id'];
    $category_id = $_POST['category_id'];       

    $query = "INSERT INTO artwork (title, description, artist_id, category_id) 
              VALUES ('$title', '$description', '$artist_id', '$category_id')";
    mysqli_query($conn, $query);
    echo "Artwork added!";
}
?>

<h2>Add Artwork</h2>
<form method="POST">
    Title: <input type="text" name="title"><br>
    Description: <textarea name="description"></textarea><br>
    Artist ID: <input type="number" name="artist_id"><br>
    Category:
    <select name="category_id">
        <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
        <?php endwhile; ?>
    </select><br>
    <input type="submit" value="Add Artwork">
</form>
<a href="dashboard.php">Back to Dashboard</a>
