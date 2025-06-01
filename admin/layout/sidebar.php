<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<div class="sidebar">
    <div class="text-center">
        <a class="navbar-brand fw-bold text-primary fs-3" href="../dashboard/dashboard.php">ArtGallery</a>
    </div>
    <div>

    </div>
    <ul>
        <li>
            <a href="../dashboard/dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                Dashboard
            </a>
        </li>
        <li>
            <a href="../artist/add-artist.php" class="<?= ($currentPage == 'add-artist.php') ? 'active' : '' ?>">
                Add Artist
            </a>
        </li>
        <li>
            <a href="../view-artist/view-artists.php"
               class="<?= ($currentPage == 'view-artists.php') ? 'active' : '' ?>">
                Manage Artists
            </a>
        </li>
        <li>
            <a href="../add-artwork/add-artwork.php" class="<?= ($currentPage == 'add-artwork.php') ? 'active' : '' ?>">
                Add Artwork
            </a>
        </li>
        <li>
            <a href="../view-artworks/view-artworks.php"
               class="<?= ($currentPage == 'view-artworks.php') ? 'active' : '' ?>">
                Manage Artworks
            </a>
        </li>
        <li>
            <a href="../add-category/add-category.php" class="<?= ($currentPage == 'add-category.php') ? 'active' : '' ?>">
                Manage Categories
            </a>
        </li>
        <li>
            <a href="../profile/profile.php" class="<?= ($currentPage == 'profile.php') ? 'active' : '' ?>">
                Profile Settings
            </a>
        </li>
    </ul>
    <div>
        <a class="text-decoration-none text-primary d-flex align-items-center gap-2 return-home-link"
           href="../homepage/home.php">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Homepage
        </a>
    </div>
</div>
