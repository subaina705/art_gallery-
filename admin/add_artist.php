<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $bio = $_POST['bio'];

    $query = "INSERT INTO artist (name, bio) VALUES ('$name', '$bio')";
    mysqli_query($conn, $query);
    echo "Artist added successfully!";
}
?>

<form method="POST">
    <h2>Add Artist</h2>
    <input type="text" name="name" placeholder="Artist Name" required><br><br>
    <textarea name="bio" placeholder="Short Bio" required></textarea><br><br>
    <button type="submit" name="submit">Add Artist</button>
</form>
