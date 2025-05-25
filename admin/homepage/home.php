<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT name, bio FROM artist LIMIT 4";
$result = mysqli_query($conn, $query);

// Query to get artworks with artist name and category name
$artwork_query = "SELECT `artwork`.*, artist.name AS artist_name, categories.name AS category_name
                  FROM `artwork`
                  JOIN artist ON `artwork`.artist_id = artist.id
                  LEFT JOIN categories ON `artwork`.category_id = categories.id
                 ";
$artwork_result = mysqli_query($conn, $artwork_query);

$artworks = [];
while($row = mysqli_fetch_assoc($artwork_result)) {
    $artworks[] = $row;
}

// Split artworks into chunks of 3
$chunks = array_chunk($artworks, 3);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtGallery - Discover Amazing Art & Artists</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="home.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary fs-3" href="#">ArtGallery</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#artists">Artists</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#artworks">Artworks</a>
                </li>
            </ul>
            <a href="../login.php" class="btn btn-primary px-4">Login</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container position-relative">
        <div class="row justify-content-center text-center text-white">
            <div class="col-lg-8">
                <h1 class="display-2 fw-bold mb-4">
                    Discover Amazing
                    <span class="d-block text-warning">Art & Artists</span>
                </h1>
                <p class="lead mb-5 text-light">
                    Explore a world of creativity and connect with talented artists from around the globe.
                    Find your next masterpiece today.
                </p>
                <button class="btn btn-warning btn-lg px-5 py-3 fw-semibold text-dark">
                    Join Now
                </button>
            </div>
        </div>
    </div>
    <!-- Decorative shapes -->
    <div class="decorative-shape shape-1"></div>
    <div class="decorative-shape shape-2"></div>
    <div class="decorative-shape shape-3"></div>
</section>

<!-- Artists Section -->
<section id="artists" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-dark mb-3">Featured Artists</h2>
            <p class="lead text-muted">Meet the talented creators in our community</p>
        </div>

        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-lg-3 col-md-6 mb-2">
                        <div class="card h-100 shadow-sm artist-card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                <p class="card-text text-muted"><?= htmlspecialchars($row['bio']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class=" text-center" role="alert">
                        <h3>No artist records found.</h3>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Artworks Section -->
<section id="artworks" class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-dark mb-3">Featured Artworks</h2>
            <p class="lead text-muted">Discover amazing pieces from our talented artists</p>
        </div>

        <div id="artworkCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($chunks as $index => $chunk): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row g-4">
                            <?php foreach ($chunk as $row): ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100 shadow-sm artwork-card">
                                        <img src="../add-artwork/<?= htmlspecialchars($row['image_path']) ?>"
                                             class="card-img-top" alt="<?= htmlspecialchars($row['title']) ?>"
                                             style="height: 250px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                                            <p class="card-text text-muted mb-1">by <?= htmlspecialchars($row['artist_name']) ?></p>
                                            <p class="card-text text-muted mb-2">Category: <?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#artworkCarousel" data-bs-slide="prev">
                <div class="carousel-control-btn">
                    <i class="bi bi-chevron-left"></i>
                </div>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#artworkCarousel" data-bs-slide="next">
                <div class="carousel-control-btn">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </button>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Logo and Description -->
            <div class="col-lg-6">
                <div class="h4 text-primary mb-3">ArtGallery</div>
                <p class="text-light mb-4">
                    Connecting artists and art lovers worldwide. Discover, collect, and showcase amazing artwork from
                    talented creators.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-light text-decoration-none hover-link">Facebook</a>
                    <a href="#" class="text-light text-decoration-none hover-link">Instagram</a>
                    <a href="#" class="text-light text-decoration-none hover-link">Twitter</a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-3">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#home" class="text-light text-decoration-none hover-link">Home</a></li>
                    <li class="mb-2"><a href="#artists" class="text-light text-decoration-none hover-link">Artists</a>
                    </li>
                    <li class="mb-2"><a href="#artworks" class="text-light text-decoration-none hover-link">Artworks</a>
                    </li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none hover-link">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-light text-decoration-none hover-link">Contact</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3">
                <h5 class="mb-3">Contact</h5>
                <div class="text-light">
                    <p class="mb-2">Email: info@artgallery.com</p>
                    <p class="mb-2">Phone: (555) 123-4567</p>
                    <p class="mb-2">Address: 123 Art Street, Creative City, CC 12345</p>
                </div>
            </div>
        </div>

        <hr class="my-4 border-secondary">
        <div class="text-center">
            <p class="mb-0 text-light">&copy; 2024 ArtGallery. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
