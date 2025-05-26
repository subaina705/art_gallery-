<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$result = mysqli_query($conn, "SELECT * FROM artist");
?>

<?php if (isset($_SESSION['alert'])): ?>
    <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['alert']['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>

<div class="mb-5 d-flex align-items-center justify-content-between">
    <h2>All Artists</h2>
    <a class="text-black" href="../artist/add-artist.php">Add New Artist</a>
</div>


<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Bio</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['bio'] ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a class="btn btn-warning btn-sm" href="../edit-artist/edit-artist.php?id=<?= $row['id'] ?>">Edit</a>
                            <a class="btn btn-danger btn-sm" href="../delete-artist/delete-artist.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this artist?')">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

