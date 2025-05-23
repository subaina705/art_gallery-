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
    <input class="form-control mt-3" type="text" name="name" placeholder="Artist Name" required>
    <textarea class="form-control mt-3" name="bio" placeholder="Short Bio" required></textarea>
    <button class="btn btn-primary mt-3" type="submit" name="submit">Add Artist</button>
</form>
