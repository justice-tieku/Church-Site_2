# Church Website - Setup Instructions

## Problem: "Can't create an account" Error

This error occurs when the database is not properly set up. Follow these steps to fix it:

## Setup Steps

### 1. Start Your Local Server
Make sure you have:
- **XAMPP**, **WAMP**, or **MAMP** installed and running
- Apache and MySQL services started

### 2. Set Up the Database

**Option A: Automatic Setup (Recommended)**
1. Open your browser
2. Navigate to: `http://localhost/Church-Site-master/setup_database.php`
3. This will automatically create the database and tables
4. You should see success messages

**Option B: Manual Setup**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "New" to create a database
3. Name it: `church_website`
4. Click on the database
5. Go to "SQL" tab
6. Copy and paste the contents of `setup_admin_table.sql`
7. Click "Go"

### 3. Register an Account
1. Go to the home page: `http://localhost/Church-Site-master/index.html`
2. Click "Login/Register" in the navigation menu
3. Click the "Register" tab
4. Fill in your details:
   - Full Name
   - Email
   - Password (minimum 6 characters)
   - Confirm Password
5. Click "Register"

### 4. Login
After successful registration:
1. Switch to the "Login" tab
2. Enter your email and password
3. Click "Login"
4. You'll be redirected to the admin panel

## Common Issues

### "Database connection failed"
- Make sure MySQL is running in XAMPP/WAMP
- Check that database credentials in `api/includes/db.php` are correct:
  - Host: `localhost`
  - Database: `church_website`
  - User: `root`
  - Password: (empty by default)

### "Email already registered"
- This email is already in use
- Try a different email or login with existing credentials

### "Registration failed"
- Run the database setup script first
- Check browser console (F12) for detailed errors

## File Structure
```
Church-Site-master/
├── api/
│   ├── includes/
│   │   └── db.php          (Database connection)
│   ├── auth.php            (Login/Register API)
│   ├── messages.php        (Messages API)
│   └── upload.php          (File upload API)
├── index.html              (Home page)
├── login.html              (Login/Register page)
├── admin.html              (Admin panel)
├── setup_database.php      (Database setup script)
└── setup_admin_table.sql   (SQL setup file)
```

## Support
If you continue to have issues:
1. Check the browser console (F12) for JavaScript errors
2. Check PHP error logs in XAMPP/WAMP
3. Verify all files are in the correct location
