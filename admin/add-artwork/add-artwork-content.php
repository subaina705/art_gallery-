<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

// Fetch categories for dropdown
$categories = mysqli_query($conn, "SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $artist_id = $_POST['artist_id'];
    $category_id = $_POST['category_id'];

    $query = "INSERT INTO add-artwork (title, description, artist_id, category_id) 
              VALUES ('$title', '$description', '$artist_id', '$category_id')";
    mysqli_query($conn, $query);
    echo "Artwork added!";
}
?>

<h2>Add Artwork</h2>
<form method="POST">
    <div class="mb-3">
        <label for="" class="form-label">Title: </label>
        <input type="text" name="title" class="form-control">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Description: </label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Artist ID:</label>
        <input type="number" name="artist_id" class="form-control">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Category:</label>
        <select name="category_id" class="form-select">
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <input type="submit" value="Add Artwork" class="btn btn-primary">
</form>
<div class="pt-3">
    <a class="mt-3" href="../dashboard/dashboard.php">Back to Dashboard</a>
</div>

