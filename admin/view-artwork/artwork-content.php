<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$result = mysqli_query($conn, "SELECT * FROM artist");

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $artist_id = $_POST['artist_id'];
    $description = $_POST['description'];

    $query = "INSERT INTO add-artwork (title, artist_id, description) 
              VALUES ('$title', '$artist_id', '$description')";
    mysqli_query($conn, $query);
    echo "Artwork added successfully!";
}
?>

<h2 class="mb-5">Add Artwork</h2>
<form method="POST">
    <div class="mb-3">
        <label for="" class="form-label">Artwork Title</label>
        <input type="text" name="title" placeholder="Artwork Title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Artist</label>
        <select name="artist_id" class="form-select" required>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" placeholder="Description" required></textarea>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Add Artwork</button>
</form>
