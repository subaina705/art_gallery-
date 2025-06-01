<?php
session_start();

// Check if admin is logged in
$isAdminLoggedIn = isset($_SESSION['admin']);

// Initialize cart as array if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// Set default content type to JSON
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Function to fetch cart items details from session cart
function fetch_cart_items($conn, $cart)
{
    $cart_items = [];
    $unique_ids = array_unique(array_map('intval', $cart));
    if (!empty($unique_ids)) {
        $ids = implode(",", $unique_ids);
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
        $cart_result = mysqli_query($conn, $cart_query);
        while ($row = mysqli_fetch_assoc($cart_result)) {
            $cart_items[] = $row;
        }
    }
    return $cart_items;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle AJAX add to cart
        if (isset($_POST['artwork_id'])) {
            $artwork_id = (int)$_POST['artwork_id'];
            if (!in_array($artwork_id, $_SESSION['cart'])) {
                $_SESSION['cart'][] = $artwork_id;
            }

            echo json_encode([
                'success' => true,
                'cart_count' => count($_SESSION['cart']),
                'cart_items' => fetch_cart_items($conn, $_SESSION['cart'])
            ]);
            exit;
        }

        // Handle AJAX remove from cart
        if (isset($_POST['remove_artwork_id'])) {
            $remove_id = (int)$_POST['remove_artwork_id'];
            if (!empty($_SESSION['cart'])) {
                $_SESSION['cart'] = array_diff($_SESSION['cart'], [$remove_id]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }

            echo json_encode([
                'success' => true,
                'cart_count' => count($_SESSION['cart']),
                'cart_items' => fetch_cart_items($conn, $_SESSION['cart'])
            ]);
            exit;
        }

        // Handle AJAX filter request
        if (isset($_POST['filter_request'])) {
            $selected_categories = isset($_POST['categories']) ?
                array_filter(array_map('intval', $_POST['categories'])) : [];
            $search_term = isset($_POST['search_term']) ? trim(mysqli_real_escape_string($conn, $_POST['search_term'])) : '';

            // Build the query with filters
            $query = "SELECT artwork.*, artist.name AS artist_name, categories.name AS category_name
                      FROM artwork
                      JOIN artist ON artwork.artist_id = artist.id
                      LEFT JOIN categories ON artwork.category_id = categories.id
                      WHERE 1=1";

            // Add category filter if any categories are selected
            if (!empty($selected_categories)) {
                $query .= " AND categories.id IN (" . implode(',', $selected_categories) . ")";
            }

            // Add search term filter if provided
            if (!empty($search_term)) {
                $query .= " AND (artwork.title LIKE '%$search_term%' 
                                OR categories.name LIKE '%$search_term%'
                                OR artist.name LIKE '%$search_term%')";
            }

            $result = mysqli_query($conn, $query);
            $filtered_artworks = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $filtered_artworks[] = $row;
            }

            echo json_encode([
                'success' => true,
                'artworks' => $filtered_artworks
            ]);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
        exit;
    }
}

// For initial page load, switch to HTML output
header('Content-Type: text/html');

// Fetch all categories
$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);
$categories = [];
while ($row = mysqli_fetch_assoc($categories_result)) {
    $categories[] = $row;
}

// Fetch artworks for initial page load
$query = "SELECT artwork.*, artist.name AS artist_name, categories.name AS category_name
          FROM artwork
          JOIN artist ON artwork.artist_id = artist.id
          LEFT JOIN categories ON artwork.category_id = categories.id";

$result = mysqli_query($conn, $query);

// Fetch cart items for initial page load
$cart_items = fetch_cart_items($conn, $_SESSION['cart']);
$cart_count = count($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collection - ArtGallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="home.css" rel="stylesheet"> <!-- Adjust path if needed -->
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary fs-3" href="index.php">ArtGallery</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="collection.php">Collections</a>
                </li>
            </ul>
            <!-- Offcanvas trigger -->
            <div class="d-flex gap-3">
                <?php if ($isAdminLoggedIn): ?>
                    <a href="../dashboard/dashboard.php" class="btn btn-primary px-4">Dashboard</a>
                <?php else: ?>
                    <a href="../login.php" class="btn btn-primary px-4">Login</a>
                <?php endif; ?>
                <a href="#" class="btn btn-outline-primary position-relative me-2" data-bs-toggle="offcanvas"
                   data-bs-target="#cartOffcanvas">
                    Bookings
                    <?php if ($cart_count > 0): ?>
                        <span id="cart-count"
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $cart_count ?>
                    </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Main Collection -->
<div class="container py-5">
    <h2 class="text-center mb-5 fw-bold">Art Collections</h2>
    <div class="row">
        <div class="col-lg-3">
            <div>
                <form id="search-form">
                    <label for="search-input" class="form-label">Search</label>
                    <input id="search-input" class="form-control" type="text" placeholder="Search Artwork">
                    <small style="font-size: 12px" class="text-muted">Search artworks by title or category</small>
                </form>
            </div>
            <div class="mt-4">
                <h5 class="mb-3">Categories:</h5>
                <form id="filter-form">
                    <?php foreach ($categories as $category): ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input category-checkbox" type="checkbox"
                                   value="<?= $category['id'] ?>" id="category-<?= $category['id'] ?>">
                            <label class="form-check-label" for="category-<?= $category['id'] ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="row" id="artworks-container">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-lg-6 col-md-6 mb-4" data-aos="fade-in">
                        <div class="card h-100 shadow-sm artwork-card">
                            <img loading="lazy" src="../add-artwork/<?= htmlspecialchars($row['image_path']) ?>" class="card-img-top"
                                 alt="<?= htmlspecialchars($row['title']) ?>"
                                 style="height: 250px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                                <p class="card-text text-muted mb-1">by <?= htmlspecialchars($row['artist_name']) ?></p>
                                <p class="card-text text-muted mb-2">
                                    Category: <?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></p>
                                <!-- AJAX Form -->
                                <form class="add-to-cart-form" data-artwork-id="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-primary w-100">Book Artwork</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- Add this alert div (initially hidden) -->
            <div id="no-artworks-alert" class="alert alert-info mt-3 d-none">
                No artworks found matching the selected category.
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas Cart -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="cartOffcanvasLabel">Your Bookings</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-between">
        <div>
            <?php if (!empty($cart_items)) : ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="card mb-3 position-relative" data-id="<?= $item['id'] ?>">
                        <div class="row g-0">
                            <div class="col-4">
                                <div class="cart-img-container">
                                    <img src="../add-artwork/<?= htmlspecialchars($item['image_path']) ?>"
                                         class="img-fluid rounded-3" alt="<?= htmlspecialchars($item['title']) ?>">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card-body d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title mb-1"><?= htmlspecialchars($item['title']) ?></h5>
                                        <p class="text-muted mb-1 fs-14">
                                            by <?= htmlspecialchars($item['artist_name']) ?></p>
                                        <p class="text-muted mb-1 fs-14">
                                            Category: <?= htmlspecialchars($item['category_name'] ?? 'Uncategorized') ?>
                                        </p>
                                    </div>
                                    <button class="btn btn-sm ms-2 remove-from-cart-btn"
                                            data-id="<?= $item['id'] ?>"><i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (!empty($cart_items)): ?>
                    <div class="d-grid cart-btn mt-4">
                        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-center">Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Helper to generate artwork HTML
    function generateArtworkHtml(artwork) {
        // Check if artwork is already in cart
        const cartItems = <?php echo json_encode($_SESSION['cart'] ?? []); ?>;
        const isBooked = cartItems.includes(artwork.id);

        return `
    <div class="col-lg-6 col-md-6 mb-4" data-aos='fade-in'>
        <div class="card h-100 shadow-sm artwork-card">
            <img src="../add-artwork/${artwork.image_path}" class="card-img-top"
                 alt="${artwork.title}"
                 style="height: 250px; object-fit: contain;">
            <div class="card-body">
                <h5 class="card-title">${artwork.title}</h5>
                <p class="card-text text-muted mb-1">by ${artwork.artist_name}</p>
                <p class="card-text text-muted mb-2">
                    Category: ${artwork.category_name || 'Uncategorized'}</p>
                <form class="add-to-cart-form" data-artwork-id="${artwork.id}">
                    <button type="submit" class="btn ${isBooked ? 'btn-success' : 'btn-primary'} w-100">
                        ${isBooked ? ' Booked <i class="fas fa-check ms-2"></i>' : 'Book Artwork'}
                    </button>
                </form>
            </div>
        </div>
    </div>`;
    }

    // Helper to generate cart item HTML
    function generateCartItemHtml(item) {
        return `
    <div class="card mb-3" data-id="${item.id}">
        <div class="row g-0">
            <div class="col-4">
<div class="cart-img-container">
                <img src="../add-artwork/${item.image_path}" class="img-fluid rounded-3" alt="${item.title}">
</div>
            </div>
            <div class="col-8">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-0">${item.title}</h5>
                        <p class="text-muted mb-1">by ${item.artist_name}</p>
                        <p class="text-muted mb-1">Category: ${item.category_name || 'Uncategorized'}</p>
                    </div>
                    <button class="btn btn-sm ms-2 remove-from-cart-btn" data-id="${item.id}"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        </div>
    </div>`;
    }

    // Update cart DOM function
    function updateCartDOM(cartCount, cartItems) {
        const cartCountElem = document.getElementById('cart-count');
        // Update or create cart count badge
        if (cartCount > 0) {
            if (cartCountElem) {
                cartCountElem.textContent = cartCount;
            } else {
                // Add badge if missing
                const bookingsBtn = document.querySelector('a.btn-outline-primary.position-relative');
                if (bookingsBtn) {
                    const badge = document.createElement('span');
                    badge.id = 'cart-count';
                    badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                    badge.textContent = cartCount;
                    bookingsBtn.appendChild(badge);
                }
            }
        } else {
            if (cartCountElem) {
                cartCountElem.remove();
            }
        }

        // Update cart offcanvas content
        const offcanvasBody = document.querySelector('.offcanvas-body > div');
        if (!offcanvasBody) return;

        if (cartItems.length === 0) {
            offcanvasBody.innerHTML = '<p class="text-center">Your cart is empty.</p>';
        } else {
            offcanvasBody.innerHTML = cartItems.map(generateCartItemHtml).join('') +
                `<div class="d-grid cart-btn mt-4">
            <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        </div>`;
        }

        // Re-bind remove buttons event listeners
        document.querySelectorAll('.remove-from-cart-btn').forEach(btn => {
            btn.addEventListener('click', removeFromCartHandler);
        });
    }

    // Filter and search artworks
    function filterArtworks() {
        const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
            .map(checkbox => checkbox.value);

        const searchTerm = document.getElementById('search-input').value;
        const alertDiv = document.getElementById('no-artworks-alert');

        // Hide alert initially
        alertDiv.classList.add('d-none');

        const formData = new FormData();
        formData.append('filter_request', true);
        selectedCategories.forEach(id => formData.append('categories[]', id));
        if (searchTerm) formData.append('search_term', searchTerm);

        fetch('collection.php', {
            method: 'POST',
            body: formData
        })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Filter request failed');

                const artworksContainer = document.getElementById('artworks-container');

                if (data.artworks.length === 0) {
                    artworksContainer.innerHTML = '';
                    alertDiv.classList.remove('d-none'); // Show alert
                } else {
                    artworksContainer.innerHTML = data.artworks.map(generateArtworkHtml).join('');
                    alertDiv.classList.add('d-none'); // Ensure alert is hidden
                }

                // Re-bind add to cart event listeners
                document.querySelectorAll('.add-to-cart-form').forEach(form => {
                    form.addEventListener('submit', addToCartHandler);
                });
            })
            .catch(error => {
                console.error('Filter Error:', error);
                alert('Failed to filter artworks. Please try again.');
            });
    }

    // Add to cart handler
    function addToCartHandler(e) {
        e.preventDefault();
        const artworkId = this.getAttribute('data-artwork-id');
        const button = this.querySelector('button');

        // Immediately update button appearance
        button.classList.remove('btn-primary');
        button.classList.add('btn-success');
        button.innerHTML = ' Booked <i class="fas fa-check ms-2"></i>';

        fetch('collection.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'artwork_id=' + encodeURIComponent(artworkId)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateCartDOM(data.cart_count, data.cart_items);
                } else {
                    // Revert button if failed
                    button.classList.remove('btn-success');
                    button.classList.add('btn-primary');
                    button.textContent = 'Book Artwork';
                }
            })
            .catch(error => {
                console.error('Add Error:', error);
                // Revert button on error
                button.classList.remove('btn-success');
                button.classList.add('btn-primary');
                button.textContent = 'Book Artwork';
            });
    }

    // Remove from cart handler function
    function removeFromCartHandler() {
        const id = this.getAttribute('data-id');

        fetch('collection.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'remove_artwork_id=' + encodeURIComponent(id)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateCartDOM(data.cart_count, data.cart_items);
                    // Find and update all buttons for this artwork
                    document.querySelectorAll(`.add-to-cart-form[data-artwork-id="${id}"] button`).forEach(button => {
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary');
                        button.textContent = 'Book Artwork';
                    });
                }
            })
            .catch(error => console.error('Remove Error:', error));
    }

    // Initial binding of event listeners
    document.addEventListener('DOMContentLoaded', function () {
        // Category checkboxes change event
        document.querySelectorAll('.category-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                console.log('Checkbox changed:', this.value, this.checked);
                filterArtworks();
            });
        });

        // Search input event with debounce
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterArtworks, 300);
        });

        // Add to cart forms
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', addToCartHandler);
        });

        // Remove from cart buttons
        document.querySelectorAll('.remove-from-cart-btn').forEach(btn => {
            btn.addEventListener('click', removeFromCartHandler);
        });
    });
</script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 400, // Animation duration
        easing: 'ease-in-out', // Easing type
    });
</script>

</body>
</html>
