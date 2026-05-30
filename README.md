# Merix Demo

A PHP/MySQL demo website for a trading-company style business site with a small admin system for material data and BOM management.

## Features

- Public website pages: Home, About, Business, Projects, Materials, Contact
- Admin login and dashboard
- Material database management
- Material image upload and management
- BOM creation, editing, preview, copy, CSV export, and XLSX export
- Admin operation logs
- Light/dark theme toggle

## Requirements

- XAMPP or similar local PHP environment
- PHP 8+
- MySQL or MariaDB

## Setup

1. Place the project under your web server directory, for example:

   `htdocs/merix`

2. Start Apache and MySQL in XAMPP.

3. Open the project in your browser:

   `http://localhost/merix/PHP/index.php`

4. The application uses the database name:

   `merix_demo`

   Database tables are created automatically by the PHP code. You can also import `merix_demo.sql` manually if needed.

## Admin Login

Admin page:

`http://localhost/merix/PHP/admin_login.php`

Default demo account:

- Username: `admin`
- Password: `admin123`

## Configuration

Database settings and demo admin credentials are in:

`PHP/includes/config.php`

Uploaded images are stored in:

`PHP/uploads`

## Notes

This is a demo project. Before using it in production, update the admin authentication, database credentials, validation rules, and security settings.
