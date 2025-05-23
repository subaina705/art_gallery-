<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Art Gallery</title>
   <style>body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    height: 100vh;
    align-items: center;
    justify-content: center;
}

.container {
    background: white;
    padding: 40px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

h1 {
    color: #333;
    margin-bottom: 30px;
}

.nav-links {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.btn {
    text-decoration: none;
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #0056b3;
}
</style>

    <!-- Optional: Bootstrap for styling -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container">
        <h1>Welcome to the Art Gallery</h1>
        <div class="nav-links">
            <a href="admin/login.php" class="btn">Admin Login</a>
            <a href="gallery.php" class="btn">View Art Gallery</a>
        </div>
    </div>
</body>
</html>
