<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$query = "SELECT pr.*, a.title AS artwork_title 
          FROM purchase_requests pr
          JOIN artwork a ON pr.artwork_id = a.id
          ORDER BY pr.request_date DESC";

$result = mysqli_query($conn, $query);
?>

<h2>Purchase Requests</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Artwork</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Message</th>
        <th>Date</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['artwork_title'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['phone'] ?></td>
        <td><?= $row['message'] ?></td>
        <td><?= $row['request_date'] ?></td>
        <td><a href="delete_request.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>

    </tr>
    <?php endwhile; ?>
</table>
