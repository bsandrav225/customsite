-- CRAFTD schema
-- Импорт: phpMyAdmin → выбрать/создать БД → Импорт → этот файл.
-- Либо выполните целиком (создаст БД castomize).
--
-- После импорта войдите в /admin/ :
--   логин: admin
--   пароль: admin123
-- Сразу смените пароль администратора в продакшене.

CREATE DATABASE IF NOT EXISTS `castomize`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `castomize`;

CREATE TABLE IF NOT EXISTS `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(64) DEFAULT NULL,
  `color` varchar(64) DEFAULT NULL,
  `size` varchar(32) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `tel` varchar(64) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `comment` text,
  `telegram` varchar(120) DEFAULT NULL,
  `details` text,
  `files` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin` (`id`, `username`, `password`)
VALUES (1, 'admin', '$2y$12$WjcFRovQH19aSleg7IMJluXy.aHMyQh37PKqdIvkKcceigBs7335y')
ON DUPLICATE KEY UPDATE
  `username` = VALUES(`username`),
  `password` = VALUES(`password`);
