# TrackLeaves — Hostinger Deployment Guide

## 📁 File Structure
```
trackleaves/
├── index.php              ← Login page
├── logout.php             ← Logout handler
├── .htaccess              ← Security & routing
├── db_setup.sql           ← Run this ONCE in phpMyAdmin
├── includes/
│   ├── db.php             ← ⚙️  Edit DB credentials here
│   ├── style.css          ← Shared styles
│   ├── header.php         ← Shared HTML header
│   └── nav.php            ← Shared bottom navigation
├── user/
│   ├── dashboard.php      ← Staff list
│   ├── add_staff.php      ← Add staff form
│   ├── apply_leave.php    ← Apply leave form
│   ├── reports.php        ← Leave reports
│   └── delete_leave.php   ← Delete handler
└── admin/
    ├── dashboard.php      ← Admin user list
    ├── add_user.php       ← Create user form
    └── all_leaves.php     ← Master leave report
```

---

## 🚀 Step-by-Step Deployment on Hostinger

### Step 1 — Create MySQL Database
1. Login to **hPanel** → **Databases** → **MySQL Databases**
2. Create a new database (e.g. `u123456_trackleaves`)
3. Create a database user and assign it to the database
4. Note down: database name, username, password

### Step 2 — Run the SQL Setup
1. In hPanel → **phpMyAdmin**
2. Select your new database from the left sidebar
3. Click the **SQL** tab
4. Open `db_setup.sql`, copy everything, paste & click **Go**
5. You should see 3 tables created + 2 default users inserted

### Step 3 — Edit Database Credentials
Open `includes/db.php` and update these 4 lines:
```php
define('DB_HOST', 'localhost');           // Keep as localhost
define('DB_NAME', 'u123456_trackleaves'); // Your database name
define('DB_USER', 'u123456_admin');       // Your DB username
define('DB_PASS', 'your_password');       // Your DB password
```

### Step 4 — Upload Files
1. In hPanel → **File Manager** → open `public_html`
2. Upload the entire `trackleaves/` folder contents directly into `public_html`
   (so `index.php` is at `public_html/index.php`)
3. Make sure `.htaccess` is uploaded too (it may be hidden — enable "Show Hidden Files")

### Step 5 — Test Login
Visit your domain. You should see the TrackLeaves login screen.

**Default credentials:**
| Role  | Email                      | Password  |
|-------|----------------------------|-----------|
| Admin | admin@trackleaves.com      | admin123  |
| User  | vikram@trackleaves.com     | user123   |

⚠️ **Change these passwords immediately** via phpMyAdmin after first login!

---

## 🔐 Security Checklist
- [ ] Change default admin password
- [ ] Change default user password
- [ ] Enable SSL in hPanel (free Let's Encrypt) and uncomment HTTPS redirect in `.htaccess`
- [ ] Do NOT expose `includes/` folder (`.htaccess` blocks it)

---

## 👥 User Roles
- **Admin** → Creates user accounts, views all leaves globally
- **User** → Manages their own staff, applies leave, views their own reports
