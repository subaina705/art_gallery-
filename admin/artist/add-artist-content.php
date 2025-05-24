<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $bio = $_POST['bio'];

    $query = "INSERT INTO artist (name, bio) VALUES ('$name', '$bio')";
    mysqli_query($conn, $query);
    if (mysqli_query($conn, $query)) {
        $successMessage = "Artist added successfully!";
    }
}
?>
<!-- Display success message if set -->
<?php if (!empty($successMessage)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $successMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="POST">
    <div class="mb-5 d-flex align-items-center justify-content-between">
        <h2>Add Artist</h2>
        <a class="text-black" href="../view-artist/view-artists.php">View Artist</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                <label for="" class="form-label">
                    Artist Name
                </label>
                <input class="form-control" type="text" name="name" placeholder="Artist Name" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">
                    Description
                </label>
                <textarea class="form-control " name="bio" placeholder="Short Bio" required></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary mt-3" type="submit" name="submit">Add Artist</button>
            </div>
        </div>
    </div>
</form>
