<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $bio = $_POST['bio'];

    $query = "INSERT INTO artist (name, bio) VALUES ('$name', '$bio')";
    if (mysqli_query($conn, $query)) {
        // Redirect to avoid form resubmission and trigger modal
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    }
}

// Check if redirected after success
$showModal = isset($_GET['success']) && $_GET['success'] == 1;
?>

<!-- Modal Structure -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h1 class="modal-title fw-bold" id="successModalLabel">Success</h1>
                <h5 class="pt-3">
                    Artist added successfully!
                </h5>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<form method="POST">
    <div class="mb-5 d-flex align-items-center justify-content-between">
        <h3 class="fw-bold">Add Artist</h3>
        <a class="btn btn-primary btn-sm" href="../view-artist/view-artists.php">View Artists</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                <label for="" class="form-label">Artist Name</label>
                <input class="form-control" type="text" name="name" placeholder="Artist Name" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Description</label>
                <textarea class="form-control" name="bio" placeholder="Short Bio" required></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary mt-3 btn-sm" type="submit" name="submit">Add Artist</button>
            </div>
        </div>
    </div>
</form>

<!-- Include Bootstrap JS (if not already included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($showModal): ?>
    <script>
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();

        // Remove the success parameter from URL after modal is shown
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
<?php endif; ?>
