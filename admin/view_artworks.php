<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$query = "SELECT artwork.*, artist.name AS artist_name, categories.name AS category_name
          FROM artwork
          JOIN artist ON artwork.artist_id = artist.id
          LEFT JOIN categories ON artwork.category_id = categories.id";

$result = mysqli_query($conn, $query);
?>

<h2>All Artworks</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Artist</th>
        <th>Description</th>
        <th>Category</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td>
            <h3><?= $row['title'] ?> <small>by <?= $row['artist_name'] ?></small></h3>
        </td>
        <td><?= $row['artist_name'] ?></td>
        <td><?= $row['description'] ?></td>
        <td><strong><?= $row['category_name'] ?></strong></td>
        <td>
            <a href="edit_artwork.php?id=<?= $row['id'] ?>">Edit</a> |
            <a href="delete_artwork.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this artwork?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
