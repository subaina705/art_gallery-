<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin'])) {
    header("Location: dashboard/dashboard.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "art_gallery_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = '';

if (isset($_POST['login'])) {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    if (empty($user) || empty($pass)) {
        $error = "Please enter both username and password.";
    } else {
        // Prevention of SQL Injection //
        $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            // Verify password (plain text comparison - INSECURE, see note below)
            if ($pass === $admin['password']) {
                $_SESSION['admin'] = [
                    'id' => $admin['id'],
                    'username' => $admin['username'],
                    'profile_image' => $admin['profile_image'],
                    'logged_in' => true
                ];

                session_regenerate_id(true);
                header("Location: dashboard/dashboard.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd 0%, #6f42c1 100%);
        }

        .btn {
            border-radius: 3px !important;
        }

        .max-height {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            max-width: 400px;
            flex: 1;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .decorative-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.2;
        }

        .shape-1 {
            width: 80px;
            height: 80px;
            background: #ffc107;
            top: 10%;
            left: 10%;
        }

        .shape-2 {
            width: 120px;
            height: 120px;
            background: #e91e63;
            bottom: 10%;
            right: 10%;
        }

        .shape-3 {
            width: 60px;
            height: 60px;
            background: #4caf50;
            top: 50%;
            left: 25%;
        }

        .home-btn {
            position: fixed;
            top: 30px;
            left: 30px;
        }
    </style>
</head>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login-style.css"> <!-- Your custom styles -->
</head>
<body>

<div class="login-background position-relative d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="home-btn">
        <a href="./homepage/home.php" class="btn btn-warning btn-lg ">Home</a>
    </div>
    <!-- Decorative Circles -->
    <div class="decorative-shape shape-1"></div>
    <div class="decorative-shape shape-2"></div>
    <div class="decorative-shape shape-3"></div>

    <!-- Login Form -->
    <div class="login-container">
        <h2 class="text-center mb-4">Admin Login</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 mt-3">Login</button>
        </form>
    </div>
</div>

</body>
</html>
