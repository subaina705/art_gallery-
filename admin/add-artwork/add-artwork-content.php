<?php
// Add error reporting at the top
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch categories and artists for dropdowns
$categories = mysqli_query($conn, "SELECT * FROM categories");
$artists = mysqli_query($conn, "SELECT * FROM artist");

// Verify queries worked
if (!$categories || !$artists) {
    die("Query failed: " . mysqli_error($conn));
}

$showModal = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $artist_id = (int)$_POST['artist_id'];
    $category_id = (int)$_POST['category_id'];

    if (isset($_FILES['artwork_image']) && $_FILES['artwork_image']['error'] == UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['artwork_image']['name']);
        $imageTmp = $_FILES['artwork_image']['tmp_name'];

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($imageTmp);
        if (!in_array($fileType, $allowedTypes)) {
            die("Error: Only JPG, PNG, and GIF files are allowed.");
        }

        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                die("Failed to create upload directory");
            }
        }

        $imagePath = $targetDir . uniqid() . '_' . $imageName;

        if (move_uploaded_file($imageTmp, $imagePath)) {
            $query = "INSERT INTO artwork (title, description, artist_id, category_id, image_path) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                die("Prepare failed: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt, "ssiis", $title, $description, $artist_id, $category_id, $imagePath);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to same page with success flag
                header("Location: ".$_SERVER['PHP_SELF']."?success=1");
                exit();
            } else {
                die("Execute failed: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } else {
            die("File upload failed");
        }
    } else {
        die("File upload error: " . $_FILES['artwork_image']['error']);
    }
}

// Show modal if redirected with success=1
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $showModal = true;
}
?>

<div class="">
    <div class="mb-5 d-flex align-items-center justify-content-between">
        <h2>Add Artwork</h2>
        <a class="btn btn-outline-dark" href="../view-artworks/view-artworks.php">View Artworks</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Upload Image</label>
                    <input type="file" name="artwork_image" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Artist</label>
                    <select name="artist_id" class="form-select" required>
                        <?php while ($artist = mysqli_fetch_assoc($artists)): ?>
                            <option value="<?= $artist['id'] ?>"><?= $artist['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php
                        // Reset pointer to loop again
                        mysqli_data_seek($categories, 0);
                        while ($cat = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Add Artwork</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal (Hidden by default, shown only after submission) -->
<?php if ($showModal): ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h1 class="modal-title fw-bold">Success!</h1>
                    <h5 class="pt-3"> Artwork added successfully!</h5>
                </div>
                <div class="modal-footer border-0 ">
                    <div class="d-flex align-items-center gap-2 w-100">
                        <a href="../view-artworks/view-artworks.php" class="btn btn-success w-100 flex-grow-1">View Artworks</a>
                        <button type="button" class="btn btn-secondary w-100 flex-grow-1" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($showModal): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            // Clean URL when modal is shown
            history.replaceState({}, document.title, window.location.pathname);

            // Clean URL when modal is closed (in case user navigates back)
            document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
                history.replaceState({}, document.title, window.location.pathname);
            });
        });
    </script>
<?php endif; ?>
