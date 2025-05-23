<?php
$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

$artwork_id = $_GET['artwork_id'];

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    $query = "INSERT INTO purchase_requests (artwork_id, name, email, phone, message)
              VALUES ('$artwork_id', '$name', '$email', '$phone', '$message')";
    mysqli_query($conn, $query);

    echo "Request submitted! <a href='gallery.php'>Go back</a>";
    exit;
}
?>

<h2>Request Purchase</h2>
<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Phone: <input type="text" name="phone" required><br><br>
    Message:<br>
    <textarea name="message" rows="4" cols="40"></textarea><br><br>
    <button type="submit" name="submit">Submit Request</button>
</form>
