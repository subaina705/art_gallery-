<?php
session_start();

// Check if admin is logged in
$isAdminLoggedIn = isset($_SESSION['admin']);

$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");
if (!$conn) {
    die("Database connection failed");
}

// Initialize error messages
$errors = [];

// Get cart IDs either from POST (form submit) or session
$cart_ids = [];
if (!empty($_POST['cart_ids'])) {
    // Sanitize and explode IDs
    $ids_raw = $_POST['cart_ids'];
    $cart_ids = array_filter(array_map('intval', explode(',', $ids_raw)));
} elseif (!empty($_SESSION['cart'])) {
    $cart_ids = $_SESSION['cart'];
}

// Validate that at least one artwork is selected
if (empty($cart_ids)) {
    $errors['artworks'] = "Please select at least one artwork to proceed with checkout";
}

// Fetch artworks from DB with artist and category info
$artworks = [];
if (!empty($cart_ids)) {
    $ids = implode(',', $cart_ids);
    $cart_query = "
        SELECT 
            artwork.*, 
            artist.name AS artist_name, 
            categories.name AS category_name
        FROM artwork
        JOIN artist ON artwork.artist_id = artist.id
        LEFT JOIN categories ON artwork.category_id = categories.id
        WHERE artwork.id IN ($ids)
    ";
    $result = mysqli_query($conn, $cart_query);
    while ($row = mysqli_fetch_assoc($result)) {
        $artworks[] = $row;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    // Validate and sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $description = mysqli_real_escape_string($conn, $_POST['orderDescription']);
    $artwork_ids = mysqli_real_escape_string($conn, $_POST['cart_ids']);

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address";
    }

    // Name validation
    if (empty($name)) {
        $errors['name'] = "Please enter your name";
    }

    // Phone validation
    if (empty($phone)) {
        $errors['phone'] = "Please enter your phone number";
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        // Insert into bookings table
        $query = "INSERT INTO bookings (customer_name, email, phone, description, artwork_ids) 
                  VALUES ('$name', '$email', '$phone', '$description', '$artwork_ids')";

        if (mysqli_query($conn, $query)) {
            $booking_id = mysqli_insert_id($conn);
            unset($_SESSION['cart']);

            // Store both message and booking ID in session
            $_SESSION['success_message'] = "Your order has been placed successfully!";
            $_SESSION['booking_id'] = $booking_id;

            header("Location: checkout.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error placing order: " . mysqli_error($conn);
            header("Location: checkout.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Checkout - ArtGallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="home.css">
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
                    <a class="nav-link" href="../homepage/home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#artists">Artists</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#artworks">Artworks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="collection.php">Collection</a>
                </li>
            </ul>
            <?php if ($isAdminLoggedIn): ?>
                <a href="../dashboard/dashboard.php" class="btn btn-primary px-4">Dashboard</a>
            <?php else: ?>
                <a href="../login.php" class="btn btn-primary px-4">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container py-5">
    <h3 class="mb-5 fw-bold">Checkout</h3>
    <div class="row">
        <!-- Left: Form -->
        <div class="col-lg-8 mb-4" data-aos="fade-right">
            <div class="card">
                <div class="card-body">
                    <!-- In the form section -->
                    <form method="post" id="checkoutForm" onsubmit="return validateForm()">
                        <?php if (isset($errors['artworks'])): ?>
                            <div class="alert alert-danger mb-4">
                                <?= $errors['artworks'] ?>
                                <a href="collection.php" class="alert-link">Browse artworks</a>
                            </div>
                        <?php endif; ?>
                        <h5 class="fw-bold mb-4">Your Information</h5>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input required type="text" class="form-control" id="name" name="name"
                                   value="<?= isset($name) ? htmlspecialchars($name) : '' ?>"/>
                            <?php if (isset($errors['name'])): ?>
                                <div class="text-danger small"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input required type="email" class="form-control" id="email" name="email"
                                   value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"/>
                            <?php if (isset($errors['email'])): ?>
                                <div class="text-danger small"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                            <div id="emailError" class="text-danger small"></div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input required type="tel" class="form-control" id="phone" name="phone"
                                   value="<?= isset($phone) ? htmlspecialchars($phone) : '' ?>"/>
                            <?php if (isset($errors['phone'])): ?>
                                <div class="text-danger small"><?= $errors['phone'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="orderDescription" class="form-label">Description</label>
                            <textarea class="form-control" name="orderDescription" id="orderDescription"
                                      rows="5"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
                        </div>

                        <!-- Pass artwork IDs for processing -->
                        <input type="hidden" name="cart_ids" value="<?= htmlspecialchars(implode(',', $cart_ids)) ?>"/>

                        <button type="submit" class="btn btn-primary btn-sm" id="submitBtn">Place Order</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: Booked Artworks -->
        <div class="col-lg-4" data-aos="fade-left">
            <?php if (!empty($artworks)): ?>
                <?php foreach ($artworks as $art): ?>
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-4">
                                <div class="cart-img-container">
                                    <img src="../add-artwork/<?= htmlspecialchars($art['image_path']) ?>"
                                         class="img-fluid"
                                         alt="<?= htmlspecialchars($art['title']) ?>"/>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($art['title']) ?></h5>
                                    <p class="card-text mb-1">
                                        <strong>Artist:</strong> <?= htmlspecialchars($art['artist_name']) ?>
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong>Category:</strong> <?= htmlspecialchars($art['category_name'] ?? 'Uncategorized') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">No artworks!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
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

<div class="modal fade" id="successModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i style="color: #53da53;margin-bottom: 40px; font-size: 100px" class="fa-solid fa-circle-check"></i>
                <h4 class="fw-bold my-3">Thank you for your order!</h4>
                <p>Your artwork will be prepared for delivery.</p>
            </div>
            <div class="modal-footer border-0">
                <a href="collection.php" class="btn btn-primary w-100">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (isset($_SESSION['success_message'])): ?>
        // Update modal content with booking ID
        document.querySelector('.modal-body p').textContent =
            "Your artwork will be prepared for delivery.";

        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();

        // Clear the success message from session
        <?php
        unset($_SESSION['success_message']);
        unset($_SESSION['booking_id']);
        ?>
        <?php endif; ?>
    });
</script>
<script>
    function validateForm() {
        // Get form elements
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const cartIds = document.querySelector('input[name="cart_ids"]').value;

        // Reset previous errors
        emailError.textContent = '';

        // Check if any artworks are selected
        if (!cartIds || cartIds.trim() === '') {
            alert('Please select at least one artwork to proceed with checkout');
            window.location.href = 'collection.php';
            return false;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            emailError.textContent = 'Please enter a valid email address';
            emailInput.focus();
            return false;
        }

        return true;
    }

    // Real-time email validation
    document.getElementById('email').addEventListener('input', function () {
        const emailError = document.getElementById('emailError');
        const email = this.value;

        if (email === '') {
            emailError.textContent = '';
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            emailError.textContent = 'Please enter a valid email address';
        } else {
            emailError.textContent = '';
        }
    });

    // Check artworks on page load
    document.addEventListener('DOMContentLoaded', function () {
        const cartIds = document.querySelector('input[name="cart_ids"]').value;
        if (!cartIds || cartIds.trim() === '') {
            const formContainer = document.querySelector('.col-lg-8');
            formContainer.innerHTML = `
                <div class="alert alert-danger">
                    Select artwork for checkout.
                    <a href="collection.php" class="alert-link">Please browse our collection</a>
                    and add items to your cart first.
                </div>
            `;
        }
    });
</script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 400, // Animation duration
        easing: 'ease-in-out', // Easing type
        once: true
    });
</script>
</body>
</html>
