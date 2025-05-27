# Art Gallery Website

A web-based art gallery management system that allows users to browse artworks and administrators to manage the gallery content.

## Project Overview

This is a PHP-based web application that serves as an art gallery platform with both public and administrative interfaces. The system allows visitors to browse artworks by category and request purchases, while administrators can manage artworks, artists, and categories through a secure admin panel.

## Features

### Public Features
- Browse artworks in the public gallery
- View artwork details including title, description, and artist information

### Admin Features
- login system
- Manage artworks (add, edit, delete, view)
- Manage artists (add, edit, delete, view)
- Manage categories (add, edit, delete, view)

## Technical Stack

### Backend
- PHP (Server-side scripting)
- MySQL Database
- Apache Web Server (XAMPP stack)

### Frontend
- HTML5
- CSS3
- Vanilla JavaScript
- Bootstrap for modern UI

### Database Structure
The system uses a MySQL database (`art_gallery_db`) with the following main tables:
- `admin`: Stores admin information
- `artwork`: Stores artwork information
- `artist`: Contains artist details
- `categories`: Manages artwork categories

## Project Structure
```
art_gallery/
├── admin/           # Admin panel files
└── README.md        # Project documentation
```

## Setup Instructions

1. Ensure you have WAMP server installed and running
2. Import the database schema (art_gallery_db)
3. Place the project files in your XAMPP/htdocs directory
4. Access the website through your local server (e.g., http://localhost/art_gallery/admin/homepage/)

## Security Features
- Admin authentication system
- SQL injection prevention using prepared statements

## Browser Compatibility
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Future Enhancements
- Image upload functionality

## Contributing
Feel free to submit issues and enhancement requests.
