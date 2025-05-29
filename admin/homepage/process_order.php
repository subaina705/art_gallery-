<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $artworks = mysqli_real_escape_string($conn, $_POST['artworks']);

    $query = "INSERT INTO orders (customer_name, email, address, artworks) 
              VALUES ('$name', '$email', '$address', '$artworks')";

    if (mysqli_query($conn, $query)) {
        // Clear cart after successful purchase
        unset($_SESSION['cart']);
        $success = true;
    } else {
        $success = false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <?php if ($success): ?>
        <!-- Success Modal -->
        <div class="alert alert-success text-center">
            <h4>✅ Purchase Successful!</h4>
            <p>Thank you for your order.</p>
            <a href="collection.php" class="btn btn-primary mt-3">Continue Browsing</a>
        </div>
    <?php else: ?>
        <div class="alert alert-danger text-center">
            <h4>❌ Order Failed!</h4>
            <p>Please try again later.</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
