-- Create the database
CREATE DATABASE novelhub_admin;
USE novelhub_admin;

-- Admin users table
CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (password: 'thecocoatrees')
INSERT INTO `admins` (`username`, `password_hash`, `email`) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@novelhub.com');

-- Authors table
CREATE TABLE `authors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100),
  `bio` TEXT,
  `avatar` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Books table
CREATE TABLE `books` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `author_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `cover_image` VARCHAR(255),
  `genre` VARCHAR(50),
  `status` ENUM('ongoing', 'completed', 'hiatus') DEFAULT 'ongoing',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`author_id`) REFERENCES `authors`(`id`)
);

-- Chapters table
CREATE TABLE `chapters` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `book_id` INT NOT NULL,
  `chapter_number` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `notes` TEXT,
  `status` ENUM('draft', 'published', 'scheduled') DEFAULT 'draft',
  `publish_date` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`book_id`) REFERENCES `books`(`id`)
);

-- Chapter views tracking
CREATE TABLE `chapter_views` (
  `chapter_id` INT NOT NULL,
  `views` INT DEFAULT 0,
  `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`chapter_id`),
  FOREIGN KEY (`chapter_id`) REFERENCES `chapters`(`id`)
);

-- Advertisements table
CREATE TABLE `advertisements` (
  `id` VARCHAR(50) PRIMARY KEY,
  `content` TEXT NOT NULL,
  `position` ENUM('top', 'middle', 'bottom') NOT NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Website settings
CREATE TABLE `website_settings` (
  `setting` VARCHAR(50) PRIMARY KEY,
  `value` TEXT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO `website_settings` (`setting`, `value`) 
VALUES 
('name', 'NovelHub'),
('logo', 'images/logo.png'),
('footer_text', '© 2023 NovelHub. All Rights Reserved.');