<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
                <img style="width: 120px" src="../logo.png" alt="Logo">
            </div>
            <div class="user-info d-flex align-items-center">
                <span class="me-3">
                    <?= htmlspecialchars($_SESSION['admin']['username']) ?>
                </span>
                <a href="../logout.php" class="btn btn-secondary m-0">Logout</a>
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
