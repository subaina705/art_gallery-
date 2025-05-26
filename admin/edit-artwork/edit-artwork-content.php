<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

// Get categories and artists for dropdowns
$categories = mysqli_query($conn, "SELECT * FROM categories");
$artists = mysqli_query($conn, "SELECT * FROM artist");

// Get the artwork id from query string
$artwork_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($artwork_id <= 0) {
    die("Invalid artwork ID.");
}

// Fetch existing artwork details
$artworkQuery = mysqli_query($conn, "SELECT * FROM artwork WHERE id = $artwork_id");
if (mysqli_num_rows($artworkQuery) == 0) {
    die("Artwork not found.");
}
$artwork = mysqli_fetch_assoc($artworkQuery);

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $artist_id = $_POST['artist_id'];
    $category_id = $_POST['category_id'];

    // Prepare image path — default is old image path
    $imagePath = $artwork['image_path'];

    // Check if new image uploaded
    if (isset($_FILES['artwork_image']) && $_FILES['artwork_image']['error'] == UPLOAD_ERR_OK) {
        $imageName = $_FILES['artwork_image']['name'];
        $imageTmp = $_FILES['artwork_image']['tmp_name'];

        $targetDir = __DIR__ . "/../add-artwork/uploads/"; // Fixed path
        $newImagePath = $targetDir . basename($imageName);

        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (move_uploaded_file($imageTmp, $newImagePath)) {
            // Optionally delete old image file here if you want:
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $imagePath = "../add-artwork/uploads/" . basename($imageName); // Relative path for DB
        } else {
            $errorMessage = "Failed to upload new image. Please check directory permissions.";
        }
    }

    if (empty($errorMessage)) {
        $query = "UPDATE artwork 
                  SET title='$title', description='$description', artist_id='$artist_id', category_id='$category_id', image_path='$imagePath' 
                  WHERE id=$artwork_id";

        if (mysqli_query($conn, $query)) {
            $successMessage = "Artwork updated successfully!";
            // Refresh the artwork data after update
            $artworkQuery = mysqli_query($conn, "SELECT * FROM artwork WHERE id = $artwork_id");
            $artwork = mysqli_fetch_assoc($artworkQuery);
        } else {
            $errorMessage = "Failed to update artwork: " . mysqli_error($conn);
        }
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

<!-- Error Alert -->
<?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $errorMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="mb-5 d-flex align-items-center justify-content-between">
    <h2>Edit Artwork</h2>
    <a class="text-black" href="../view-artworks/view-artworks.php">View Artworks</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="../add-artwork/uploads/<?= htmlspecialchars(basename($artwork['image_path'])) ?>"
                     alt="Artwork Image" style="max-width: 200px; max-height: 150px;">

            </div>
            <div class="mb-3">
                <label for="artwork_image" class="form-label">Upload New Image (optional)</label>
                <input type="file" name="artwork_image" class="form-control" id="artwork_image">
            </div>
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($artwork['title']) ?>"
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"
                          required><?= htmlspecialchars($artwork['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Artist</label>
                <select name="artist_id" class="form-select" required>
                    <?php
                    // Reset pointer to loop again
                    mysqli_data_seek($artists, 0);
                    while ($artist = mysqli_fetch_assoc($artists)): ?>
                        <option value="<?= $artist['id'] ?>" <?= ($artist['id'] == $artwork['artist_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($artist['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $artwork['category_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="d-flex justify-content-end">
                <input type="submit" value="Update Artwork" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
