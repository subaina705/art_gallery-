<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

// Fetch categories for dropdown
$categories_result = mysqli_query($conn, "SELECT * FROM categories");

// Prepare filter condition
$category_filter = "";
if (isset($_GET['category_id']) && $_GET['category_id'] != "") {
    $category_id = mysqli_real_escape_string($conn, $_GET['category_id']);
    $category_filter = "WHERE add-artwork.category_id = $category_id";
}

// Query artworks with optional filter
$query = "SELECT add-artwork.id, add-artwork.title, add-artwork.description, artist.name AS artist_name, categories.name AS category_name
          FROM add-artwork
          JOIN artist ON add-artwork.artist_id = artist.id
          LEFT JOIN categories ON add-artwork.category_id = categories.id
          $category_filter";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Art Gallery</title>
    <style>
        .artwork {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            width: 60%;
        }
    </style>
</head>
<body>
    <h1>Public Art Gallery</h1>

    <!-- Filter by category -->
    <form method="GET">
        <label for="category_id">Filter by Category:</label>
        <select name="category_id" id="category_id" onchange="this.form.submit()">
            <option value="">-- All Categories --</option>
            <?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>
                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= $cat['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <!-- Display artworks -->
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="artwork">
            <h3><?= $row['title'] ?> <small>by <?= $row['artist_name'] ?></small></h3>
            <p><?= $row['description'] ?></p>
            <p><strong>Category:</strong> <?= $row['category_name'] ?></p>
            <a href="request_purchase.php?artwork_id=<?= $row['id'] ?>">Request Purchase</a>
        </div>
    <?php endwhile; ?>
</body>
</html>
