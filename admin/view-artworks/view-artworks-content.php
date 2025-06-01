<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$query = "SELECT `artwork`.*, artist.name AS artist_name, categories.name AS category_name
          FROM `artwork`
          JOIN artist ON `artwork`.artist_id = artist.id
          LEFT JOIN categories ON `artwork`.category_id = categories.id";


$result = mysqli_query($conn, $query);
?>
<div class="mb-5 d-flex align-items-center justify-content-between">
    <h3 class="fw-bold">Artworks</h3>
    <a class="btn btn-primary btn-sm" href="../add-artwork/add-artwork.php">Add Artwork</a>
</div>

<?php if (mysqli_num_rows($result) > 0): ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="col">
                <div class="card h-100">
                    <?php if (!empty($row['image_path'])): ?>
                        <img loading="lazy" src="../add-artwork/<?= htmlspecialchars($row['image_path']) ?>" class="card-img-top"
                             alt="Artwork Image" style="object-fit: contain; height: 200px;">
                    <?php else: ?>
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="200"
                             xmlns="http://www.w3.org/2000/svg"
                             role="img" aria-label="Placeholder: No Image" preserveAspectRatio="xMidYMid slice"
                             focusable="false">
                            <title>No Image</title>
                            <rect width="100%" height="100%" fill="#868e96"></rect>
                            <text x="50%" y="50%" fill="#dee2e6" dy=".3em" text-anchor="middle">No Image</text>
                        </svg>
                    <?php endif; ?>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($row['title']) ?></h5>
                        <h6 class="card-subtitle mb-0 text-muted fs-14">by <?= htmlspecialchars($row['artist_name']) ?></h6>
                        <p class="card-text flex-grow-1 text-muted my-2 fs-14"><i><?= nl2br(htmlspecialchars($row['description'])) ?></i></p>
                        <p class="fs-14"><strong>Category:</strong> <?= htmlspecialchars($row['category_name']) ?></p>
                        <div class="mt-auto d-flex gap-4 justify-content-end">
                            <a href="../edit-artwork/edit-artwork.php?id=<?= $row['id'] ?>"
                               class="text-black fs-18"><i class="fa-solid fa-pen"></i></a>
                            <a href="../delete-artwork/delete_artwork.php?id=<?= $row['id'] ?>"
                               class="text-danger fs-18" onclick="return confirm('Delete this artwork?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        No artworks found. Click the "Add Artwork" button to add a new artwork.
    </div>
<?php endif; ?>
