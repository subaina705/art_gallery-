<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT artwork.*, artist.name AS artist_name, categories.name AS category_name
          FROM artwork
          JOIN artist ON artwork.artist_id = artist.id
          LEFT JOIN categories ON artwork.category_id = categories.id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collection - ArtGallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../homepage/home.css" rel="stylesheet"> <!-- Adjust path if needed -->
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary fs-3" href="index.php">ArtGallery</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="collection.php">Collection</a>
                </li>
            </ul>
            <a href="cart.php" class="btn btn-outline-primary me-2">View Cart</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="text-center mb-4">Art Collection</h2>
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm artwork-card">
                    <img src="../add-artwork/<?= htmlspecialchars($row['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['title']) ?>" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="card-text text-muted mb-1">by <?= htmlspecialchars($row['artist_name']) ?></p>
                        <p class="card-text text-muted mb-2">Category: <?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></p>
                        <form method="post" action="add_to_cart.php">
                            <input type="hidden" name="artwork_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
