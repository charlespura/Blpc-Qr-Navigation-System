# BLPC QR Navigation System
<img width="800" height="500" alt="Screenshot 2026-03-25 at 12 58 04 AM" src="https://github.com/user-attachments/assets/88c58906-fb50-455e-b519-47eada624d5c" />
<img width="800" height="500" alt="Screenshot 2026-03-25 at 12 58 20 AM" src="https://github.com/user-attachments/assets/2d6e3fa7-1e01-49bc-a7e7-c0de15e3d393" />

A web-based QR code navigation system for gated communities. Guards manage residents and generate QR codes that open Google Maps directly to a resident's exact location.

## Features

- Guard dashboard with resident CRUD (add, edit, delete, view)
- Auto-location button using the browser's Geolocation API
- QR codes that link straight to Google Maps (no intermediate page)
- Resident name/address shown on the QR page for verification
- Copy-to-clipboard for the map link
- Works on any smartphone camera

## Tech Stack

- Frontend: HTML5, Tailwind CSS, Font Awesome
- Backend: PHP
- Database: MySQL
- QR generation: QR Server API (no API key required)

## Project Structure

```
BlpcQrNavigationSystem/
├── index.php           # Guard dashboard with resident management
├── generate_qr.php     # QR code generation page
├── view.php            # Resident info page (fallback, not used in main flow)
├── save_resident.php   # Save new resident to database
├── update_resident.php # Update existing resident
├── db.php              # Database connection configuration
└── database.sql        # MySQL database setup script
```

## Setup

### 1) Prerequisites

- PHP 7.0+
- MySQL 5.6+
- Web server (Apache/XAMPP/WAMP/MAMP)

### 2) Database

1. Create the database and table:

```sql
CREATE DATABASE IF NOT EXISTS blpc;
USE blpc;

CREATE TABLE residents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  address VARCHAR(255) NOT NULL,
  map_link TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

2. Or import `database.sql` in phpMyAdmin/MySQL CLI.

### 3) Configure

Update credentials in `db.php` if needed:

```php
$conn = new mysqli("localhost", "root", "", "blpc");
```

### 4) Run

Open:

```
http://localhost/BlpcQrNavigationSystem/index.php
```

## Usage

### Add a resident

1. Click "Add New Resident"
2. Enter name and address
3. For the map link:
   - Click "Auto" to capture current coordinates, or
   - Paste a Google Maps coordinate link
4. Click "Save Resident"

### Generate a QR code

1. Find the resident in the table
2. Click the QR button
3. Show the QR code to the rider

### Rider scan flow

1. Open phone camera
2. Scan the QR code
3. Tap the notification to open Google Maps

## Google Maps Link Format

```
https://maps.google.com/?q=latitude,longitude
```

Example:

```
https://maps.google.com/?q=14.6592196857,121.032157308
```

## Troubleshooting

- QR not scanning: increase screen brightness or use a QR scanner app
- Auto-location not working: allow location permission and enable GPS
- Database connection error: verify MySQL is running and `db.php` credentials match
- QR image not showing: QR Server API requires internet access

## Security Notes

- Intended for local network use
- Add authentication if exposed publicly
- Use HTTPS if accessed outside localhost

## License

Free to use for gated communities and private subdivisions.
