<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
// Connect to database and get current admin data
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");
$admin_id = $_SESSION['admin']['id'];
$stmt = $conn->prepare("SELECT username FROM admin WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin_data = $result->fetch_assoc();

// Always update session with the fresh data
$_SESSION['admin']['username'] = $admin_data['username'];
$stmt->close();
$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="app">
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <header class="top-nav">
            <div class="logo">
                <!--                <a class="navbar-brand fw-bold text-primary fs-3" href="../homepage/home.php">ArtGallery</a>-->
            </div>
            <div class="user-info d-flex align-items-center gap-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <span>
            <?= htmlspecialchars($_SESSION['admin']['username']) ?>
        </span>
                </div>
                <div style="color: #cacaca">
                    |
                </div>
                <a href="../logout.php" class="btn btn-primary btn-sm m-0">Logout</a>
            </div>
        </header>

        <main class="dashboard-content">
            <?php include $page; ?>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
</body>
</html>
