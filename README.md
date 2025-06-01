# Art Gallery Management System

A comprehensive web-based art gallery management system that allows administrators to manage artworks, artists, and categories efficiently.

## 🚀 Features

- **Admin Authentication**
  - Secure login system
  - Session management
  - Protected admin dashboard

- **Artwork Management**
  - Add new artworks
  - View artwork details
  - Edit existing artworks
  - Delete artworks
  - Categorize artworks

- **Artist Management**
  - Add new artists
  - View artist profiles
  - Edit artist information
  - Delete artist records

- **Category Management**
  - Add new categories
  - Organize artworks by categories

## 🛠️ Technology Stack

### Backend
- PHP 8.0+
- MySQL Database
- Session-based Authentication

### Frontend
- HTML5
- CSS3
- Bootstrap 5.3
- JavaScript

### Libraries and Dependencies
- Bootstrap 5.3.6 (CSS Framework)
  - Used for responsive design
  - Pre-built components
  - Grid system
  - Form styling

- External CDN Packages
  - Animation Libraries (AOS + Animate.css)
    - Used for smooth transitions and effects
    - Enhances user experience with dynamic elements

## 📁 Project Structure

```
art_gallery/
├── admin/
│   ├── add-artwork/      # Artwork creation functionality
│   ├── add-category/     # Category management
│   ├── artist/          # Artist management
│   ├── dashboard/       # Admin dashboard
│   ├── edit-artwork/    # Artwork editing
│   ├── edit-artist/     # Artist editing
│   ├── view-artwork/    # Artwork viewing
│   ├── view-artist/     # Artist profile viewing
│   ├── homepage/        # Public homepage
│   ├── layout/          # Global layout components
│   ├── login.php        # Admin authentication
│   └── logout.php       # Session termination
```

## 🔧 Installation

1. **Prerequisites**
   - XAMPP Server (Windows)
   - PHP 8.0 or higher
   - MySQL 5.7 or higher

2. **Database Setup**
   - Create a new database named `art_gallery_db`
   - Import the database schema (if provided)

3. **Configuration**
   - Update database credentials in `admin/login.php`:
     ```php
     $conn = mysqli_connect("localhost", "root", "", "art_gallery_db");
     ```

4. **File Structure**
   - Place all files in your XAMPP's htdocs directory
   - Ensure proper file permissions

## 🔐 Security Features

- Session-based authentication
- Password protection for admin area
- Prepared statements for database queries

## 🎨 UI/UX Features

- Responsive design
- Modern gradient background
- Decorative elements
- Bootstrap-based components
- User-friendly navigation
- Clean and intuitive interface

## 🚨 Security Notes

The current implementation uses plain text password comparison, which is not secure for production use. It is recommended to:

1. Implement password hashing (e.g., using `password_hash()` and `password_verify()`)
2. Use HTTPS for all communications
3. Implement CSRF protection
4. Add rate limiting for login attempts

## 📝 Usage

1. Access the admin panel through `admin/login.php`
2. Log in with your credentials
3. Navigate through the dashboard to manage:
   - Artworks
   - Artists
   - Categories

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request


## 👥 Authors

- Subaina Tehreem - Initial work

## 🙏 Acknowledgments

- Bootstrap team for the amazing UI framework
- XAMPP Server for the development environment 
