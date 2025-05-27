<?php
function countDatabaseItems($tableName)
{
    $conn = mysqli_connect("localhost", "root", "", "art_gallery_db");
    if ($conn->connect_error) {
        return "Error";
    }

    $result = $conn->query("SELECT COUNT(*) as total FROM $tableName");
    $row = $result->fetch_assoc();
    $count = $row['total'];

    $conn->close();
    return $count;
}
?>

<!-- Dashboard Summary Cards -->
<div class="dashboard-summary row mb-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6>Total Artists</h6>
                <h3><?php echo countDatabaseItems('artist'); ?></h3>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6>Total Artworks</h6>
                <h3><?php echo countDatabaseItems('artwork'); ?></h3>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6>Total Categories</h6>
                <h3><?php echo countDatabaseItems('categories'); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Message -->
<div class="container mt-4">
    <div class="text-center mt-5" role="alert">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['admin']['username']) ?>!</h1>
    </div>
</div>

<!--
<div class="main_img">
    <img src="../main_img.webp" alt="">

    <div class="txt">
        <h1>New This Week</h1>
        <p>Discover New Art Our Curators Love Every Week</p>
    </div>

    <div class="top_button">
        <a href="#">
            <button>Explore</button>
        </a>
    </div>
</div>
-->
