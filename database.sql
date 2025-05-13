CREATE DATABASE IF NOT EXISTS rbac_system;
USE rbac_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'contributor', 'user', 'pending') DEFAULT 'pending',
    status ENUM('approved', 'rejected', 'pending') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name ENUM('admin', 'editor', 'contributor', 'user') NOT NULL
);

CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE role_permissions (
    role_id INT,
    permission_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id),
    PRIMARY KEY (role_id, permission_id)
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert default roles
INSERT INTO roles (name) VALUES ('admin'), ('editor'), ('contributor'), ('user');

-- Insert default permissions
INSERT INTO permissions (name) VALUES 
('manage_users'), 
('edit_users'), 
('manage_posts'), 
('add_posts'), 
('edit_posts');

-- Assign permissions to roles
INSERT INTO role_permissions (role_id, permission_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), -- Admin: all permissions
(2, 2), (2, 3), (2, 4), (2, 5), -- Editor: edit users and manage posts
(3, 3), (3, 4), (3, 5), -- Contributor: manage posts
(4, 4); -- User: add posts only

-- Insert default admin user
INSERT INTO users (name, email, password, role, status) VALUES
('Admin User', 'admin@example.com', '$2y$10$3Qz7y9xQzQ1z6aNeV7b1sZ9k9q3zW5y7xQzQ1z6aNeV7b1sZ9k9q3', 'admin', 'approved');
-- Password: admin123 (hashed with bcrypt)