<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['artwork_id'])) {
    $artwork_id = $_POST['artwork_id'];

    if (!in_array($artwork_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $artwork_id;
    }
}

header("Location: collection.php");
exit;
