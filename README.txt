============================================================
  UMS LOST & FOUND HUB
  CP0025 Web Programming — Group Assignment 2026
============================================================

SETUP INSTRUCTIONS
------------------

1. INSTALL XAMPP
   - Download and install XAMPP from https://www.apachefriends.org
   - Start Apache and MySQL from the XAMPP Control Panel.

2. COPY PROJECT FILES
   - Copy the entire project folder into:
       C:\xampp\htdocs\lost_found_hub\
   - Make sure this folder contains all .php, style.css, and the sql file.

3. CREATE THE DATABASE
   - Open your browser and go to: http://localhost/phpmyadmin
   - Click "New" to create a new database named:  lost_found_db
   - Select the database, click the "Import" tab.
   - Choose the file:  lost_found_db.sql
   - Click "Go" to import. The table and sample data will be created.

4. CONFIGURE db_config.php (if needed)
   - Default settings are for XAMPP (root user, empty password).
   - If your MySQL has a password, edit db_config.php:
       $pass = "your_password_here";

5. CREATE UPLOADS FOLDER
   - Inside your project folder, create a folder named:  uploads/
   - This folder stores all uploaded item images.
   - It is created automatically on first run, but you can create it manually.

6. RUN THE APPLICATION
   - Open your browser and go to:
       http://localhost/lost_found_hub/index.php
   - You should see the UMS Lost & Found Hub front page.

============================================================
FILE STRUCTURE
============================================================

  lost_found_hub/
  │
  ├── index.php          → Front Page (landing page, group members info)
  ├── dashboard.php      → READ Dashboard (search, filter, table)
  ├── add_item.php       → CREATE Form (image upload, INSERT)
  ├── edit.php           → UPDATE Form (edit record, replace image)
  ├── delete.php         → DELETE Logic (safe deletion, image cleanup)
  ├── db_config.php      → Database Connection
  ├── style.css          → All CSS Styling
  ├── lost_found_db.sql  → Database Schema + Sample Data
  ├── uploads/           → (create this folder) Uploaded images go here
  └── README.txt         → This file

============================================================
GROUP ROLES
============================================================

  Member 1 (Logic Architect)     → add_item.php
  Member 2 (Data Specialist)     → dashboard.php
  Member 3 (Modification Lead)   → edit.php
  Member 4 (Security & Maint.)   → delete.php
  Member 5 (UI/UX & Integration) → style.css + index.php

============================================================
CUSTOMIZATION NOTES
============================================================

  - Replace "Member X Name" and "Student ID: CB######" in index.php
    with your actual group members' names and student IDs.

  - To add a real photo for a group member, place the image in
    the project folder and replace the placeholder div:
        <div class="photo-placeholder">...</div>
    with:
        <img src="images/member1.jpg" alt="Member Name">

  - To change the database name, update the $dbname variable
    in db_config.php and rename the database in phpMyAdmin.

============================================================
