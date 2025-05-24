<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

// Fetch categories for dropdown
$categories = mysqli_query($conn, "SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $artist_id = $_POST['artist_id'];
    $category_id = $_POST['category_id'];

    if (isset($_FILES['artwork_image']) && $_FILES['artwork_image']['error'] == UPLOAD_ERR_OK) {
        $imageName = $_FILES['artwork_image']['name'];
        $imageTmp = $_FILES['artwork_image']['tmp_name'];

        $targetDir = "uploads/";
        $imagePath = $targetDir . basename($imageName);

        if (move_uploaded_file($imageTmp, $imagePath)) {
            $query = "INSERT INTO artwork (title, description, artist_id, category_id, image_path) 
                  VALUES ('$title', '$description', '$artist_id', '$category_id', '$imagePath')";
            if (mysqli_query($conn, $query)) {
                $successMessage = "Artwork added successfully!";
            }
        } else {
            $successMessage = "Failed to upload image.";
        }
    } else {
        $successMessage = "No image uploaded or upload failed.";
    }
}
?>

<!-- Success Alert -->
<?php if (!empty($successMessage)): ?>
    <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $successMessage ?> <a href="../view-artworks/view-artworks.php" class="alert-link">View Artworks</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="mb-5 d-flex align-items-center justify-content-between">
    <h2>Add Artwork</h2>
    <a class="text-black" href="../view-artworks/view-artworks.php">View Artworks</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="" class="form-label">Upload Image</label>
                <input type="file" name="artwork_image" class="form-control">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Title </label>
                <input type="text" name="title" class="form-control">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Description </label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Artist ID</label>
                <input type="number" name="artist_id" class="form-control">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="d-flex justify-content-end">
                <input type="submit" value="Add Artwork" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>

