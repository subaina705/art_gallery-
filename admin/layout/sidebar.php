<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<div class="sidebar">
    <h2 class="mb-4">Admin</h2>
    <ul>
        <li><a href="../dashboard/dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">Dashboard</a></li>
        <li><a href="../artist/add-artist.php" class="<?= ($currentPage == 'add-artist.php') ? 'active' : '' ?>">Add Artist</a></li>
        <li><a href="../view-artist/view-artists.php" class="<?= ($currentPage == 'view-artists.php') ? 'active' : '' ?>">Manage Artists</a></li>
        <li><a href="../add-artwork/add-artwork.php" class="<?= ($currentPage == 'add-artwork.php') ? 'active' : '' ?>">Add Artwork</a></li>
        <li><a href="../view-artworks/view-artworks.php" class="<?= ($currentPage == 'view-artworks.php') ? 'active' : '' ?>">Manage Artworks</a></li>
<!--        <li><a href="#" class="">Logout</a></li>-->
    </ul>
</div>
