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
<div>
    <h3 class="fw-bold mb-5">Dashboard</h3>
</div>

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
