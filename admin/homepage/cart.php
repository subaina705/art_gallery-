<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$cart = $_SESSION['cart'] ?? [];
$items = [];

if (!empty($cart)) {
    $ids = implode(",", array_map('intval', $cart));
    $query = "SELECT * FROM artwork WHERE id IN ($ids)";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart - ArtGallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #checkout-form {
            display: none;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Your Cart</h2>
    <?php if (!empty($items)) : ?>
        <div class="row">
            <?php foreach ($items as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="../add-artwork/<?= htmlspecialchars($item['image_path']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="text-muted">Artwork ID: <?= $item['id'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Checkout Button -->
        <button class="btn btn-primary mt-3" onclick="document.getElementById('checkout-form').style.display='block'">Proceed to Checkout</button>

        <!-- Checkout Form -->
        <div id="checkout-form" class="mt-4">
            <h4>Enter Your Details</h4>
            <form method="post" action="process_order.php">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" required class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" required class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" required class="form-control"></textarea>
                </div>
                <input type="hidden" name="artworks" value="<?= implode(',', array_column($items, 'id')) ?>">
                <button type="submit" class="btn btn-success">Submit Order</button>
            </form>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>
</body>
</html>
