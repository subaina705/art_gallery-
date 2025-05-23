<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #ecf0f1;
    }

    /* Top navbar */
    nav {
      display: flex;
      align-items: center;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 80px;
      background-color: #ffffff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .logo {
      position: absolute;
      left: 20px;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 30px;
      margin: 0 auto;
    }

    nav ul a {
      text-decoration: none;
      font-size: 1.125rem;
      color: black;
      font-weight: 500;
    }

    nav ul a:hover {
      background-color: rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      padding: 12px;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 80px; /* below new navbar */
      left: 0;
      width: 220px;
      height: calc(100vh - 80px);
      background-color:#efefe6;
      color: black;
      padding: 20px;
      overflow-y: auto;
    }

    .sidebar h2 {
      margin-bottom: 20px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar li {
      margin-bottom: 10px;
    }

    .sidebar a {
      color: black;
      text-decoration: none;
      display: block;
      padding: 10px;
      border-radius: 4px;
    }

    .sidebar a:hover {
      background-color: rgba(0, 0, 0, 0.1);
    }

   .content {
  margin-top: 80px;
  margin-left: 0;
  padding: 0;
}
    .toggle-btn {
      display: none;
    }
    /* Main image section */
.main_img {
  position: absolute;
  top: 80px; /* height of the navbar */
  left: 220px; /* width of the sidebar */
  width: calc(100vw - 220px); /* subtract sidebar */
  height: calc(100vh - 80px); /* subtract navbar */
  z-index: 0;
}

.main_img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.txt {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  color: rgba(0, 0, 0, 0.7);
  z-index: 2;
}

.txt p {
  font-size: 14px;
  margin-top: 8px;
}

.top_button {
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
}

.top_button button {
  padding: 14px 77px;
  font-size: 20px;
  letter-spacing: 0.5px;
  font-weight: 500;
  background-color: rgb(51, 51, 51);
  color: whitesmoke;
  border: none;
}

.down_txt {
  margin-top: 60px;
  text-align: center;
}

.down_txt h2 {
  font-size: 25px;
  font-weight: 700;
  color: #333;
  padding-top: 17px;
}


    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 999;
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .content {
        margin-left: 0;
      }

      .toggle-btn {
        display: block;
        background-color: #2c3e50;
        color: white;
        padding: 10px;
        margin-bottom: 10px;
        cursor: pointer;
      }
    }

  </style>
</head>

<body>

  <!-- New navbar -->
  <nav>
    <div class="logo">
      <img height="70px" src="logo.png" alt="Logo">
    </div>
    <ul>
      <li><a href="#home">Home</a></li>
      <li><a href="#artists">Artists</a></li>
      <li><a href="#categories">Categories</a></li>
      <li><a href="#exhibitions">Exhibitions</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="add_artist.php">Add Artist</a></li>
      <li><a href="add_artwork.php">Add Artwork</a></li>
      <li><a href="view_artworks.php">View Artworks</a></li>
      <li><a href="view_requests.php">View Purchase Requests</a></li>
      <li><a href="view_artists.php">Manage Artists</a></li>
      <li><a href="#">Dashboard</a></li>
      <li><a href="#">Users</a></li>
      <li><a href="#">Settings</a></li>
      <li><a href="#">Logout</a></li>
    </ul>
  </div>

  <!-- Main content -->
  <div class="content">
  <div class="toggle-btn" onclick="toggleSidebar()">☰ Menu</div>

  <div class="main_img">
    <img src="main_img.webp" alt="">
    
    <div class="txt">
      <h1>New This Week</h1>
      <p>Discover New Art Our Curators Love Every Week</p>
    </div>

    <div class="top_button">
      <a href="#"><button>Explore</button></a>
    </div>
  </div>

  <div class="down_txt">
    <h2>Discover Art You Love From the World's Leading Online Gallery</h2>
  </div>
</div>


  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }
  </script>

</body>
</html>
