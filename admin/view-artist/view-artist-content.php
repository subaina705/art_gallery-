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
    <h3 class="fw-bold">All Artists</h3>
    <a class="btn btn-primary btn-sm" href="../artist/add-artist.php">Add New Artist</a>
</div>


<div class="card">
    <div class="card-body">
        <table class="table table-hover align-middle">
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
                        <div class="d-flex align-items-center justify-content-end gap-3">
                            <a class="text-black" href="../edit-artist/edit-artist.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-pen"></i></a>
                            <a class="text-danger" href="../delete-artist/delete-artist.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this artist?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

