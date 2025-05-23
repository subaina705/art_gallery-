<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$result = mysqli_query($conn, "SELECT * FROM artist");
?>

<h2>All Artists</h2>
<a href="add_artist.php">Add New Artist</a><br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Bio</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['bio'] ?></td>
        <td>
            <a href="edit_artist.php?id=<?= $row['id'] ?>">Edit</a> |
            <a href="delete_artist.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this artist?')">Delete</a>
            <a href="dashboard.php">Back to Dashboard</a><br><br>

        </td>
    </tr>
    <?php endwhile; ?>
</table>
