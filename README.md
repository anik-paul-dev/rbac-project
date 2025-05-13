RBAC System
A secure Role-Based Access Control (RBAC) system built with PHP, MySQL, HTML, CSS, Bootstrap, and JavaScript.
Setup Instructions

Clone the repository:
git clone https://github.com/your-repo/rbac_project.git
cd rbac_project


Set up the database:

Create a MySQL database named rbac_system.
Import database.sql using phpMyAdmin or:mysql -u root -p rbac_system < database.sql


Update .env with your database credentials:DB_HOST=localhost
DB_USER=your_username
DB_PASS=your_password
DB_NAME=rbac_system
JWT_SECRET=your_jwt_secret_key




Run the application:

Use PHP’s built-in server:php -S localhost:8000 -t public


Or configure Apache with document root set to rbac_project/public/.
Access http://localhost:8000/login.php.


Access the application:

Default admin credentials: admin@example.com / admin123.



Features

User registration with role selection (Editor, Contributor, User).
Admin approval system with AJAX support.
Role-based dashboards with restricted access.
Secure authentication with bcrypt password hashing.
Email and on-site notifications for registration and approval.
Search and filter users in the admin dashboard.
REST API with JWT authentication for user and post management.

API Endpoints

GET /api.php?path=users: List all users (Admin only).
POST /api.php?path=user/approve: Approve a user (Admin only).
GET /api.php?path=posts: List all blog posts.
POST /api.php?path=posts: Add a blog post (Authorized roles only).

Example API Request:
curl -H "Authorization: Bearer <your_jwt_token>" http://localhost:8000/api.php?path=users

Folder Structure

config/ - Database configuration.
controllers/ - Backend logic for authentication, users, and blogs.
middleware/ - Authentication and access control.
views/ - Frontend templates.
public/ - Publicly accessible files, including API endpoint.
assets/ - CSS and JavaScript files.
notifications/ - Email notification scripts.
uploads/ - Placeholder for file uploads.

Security

Passwords hashed with bcrypt.
SQL injection prevented with prepared statements.
XSS prevented with htmlspecialchars().
Secure headers in .htaccess.
JWT-based API authentication.



C:\Database\htdocs\RBAC_project\
  ├── assets\
  │   ├── css\
  │   │   └── style.css
  │   └── js\
  │       └── scripts.js
  ├── config\
  │   └── db.php
  ├── controllers\
  │   ├── AuthController.php
  │   ├── BlogController.php
  │   └── UserController.php
  ├── middleware\
  │   └── auth.php
  ├── notifications\
  │   ├── send_approval_notice.php
  │   └── send_registration_alert.php
  ├── public\
  │   ├── api.php
  │   ├── approve_user.php
  │   ├── dashboard.php
  │   ├── index.php
  │   ├── login.php
  │   ├── logout.php
  │   ├── not_approved.php
  │   └── register.php
  ├── vendor\
  ├── views\
  │   ├── auth\
  │   │   ├── login.php
  │   │   └── register.php
  │   ├── dashboard\
  │   │   ├── admin.php
  │   │   ├── contributor.php
  │   │   ├── editor.php
  │   │   └── user.php
  │   ├── layout\
  │   │   ├── footer.php
  │   │   ├── header.php
  │   │   └── navbar.php
  │   └── partials\
  │       └── approval_notice.php
  ├── uploads\
  ├── .env
  ├── .htaccess
  ├── composer.json
  ├── database.sql
  └── README.md