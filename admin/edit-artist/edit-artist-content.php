<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM artist WHERE id='$id'");
$artist = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $bio = $_POST['bio'];

    if (mysqli_query($conn, "UPDATE artist SET name='$name', bio='$bio' WHERE id='$id'")) {
        $successMessage = "Artist updated successfully!";
        // Refresh artist data after update (optional)
        $result = mysqli_query($conn, "SELECT * FROM artist WHERE id='$id'");
        $artist = mysqli_fetch_assoc($result);
    }
}
?>

<!-- Alert message code -->
<?php if (!empty($successMessage)): ?>
    <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $successMessage ?> <a href="../view-artist/view-artists.php" class="alert-link">Back to list</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="mb-5">
    <h2>Edit Artist</h2>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label for="" class="form-label">
                    Name
                </label>
                <input class="form-control" type="text" name="name" value="<?= $artist['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Bio</label>
                <textarea class="form-control" name="bio" rows="4" cols="40"><?= $artist['bio'] ?></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update Artist</button>
        </form>
    </div>
</div>

