-- init.sql
-- Αυτό το αρχείο τρέχει αυτόματα από το MySQL container κατά την πρώτη εκκίνηση

CREATE DATABASE IF NOT EXISTS `my_database`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `my_database`;

-- Πίνακας χρηστών
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `firstname` VARCHAR(100) NOT NULL,
  `lastname` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Πίνακας λιστών
CREATE TABLE `playlists` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_email` VARCHAR(100) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `is_public` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Πίνακας στοιχείων λίστας
CREATE TABLE `playlist_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `playlist_id` INT NOT NULL,
  `youtube_id` VARCHAR(50) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `added_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`playlist_id`) REFERENCES `playlists`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
