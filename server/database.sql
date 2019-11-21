CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) UNIQUE NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL
);

INSERT INTO `users` (`username`, `email`, `password`) VALUES ('bplaat', 'bastiaan.v.d.plaat@gmail.com', '$2y$10$HmCHKlsETp21lyR86xv23uh/ElHoER6OOhGCw64O9pbjXa0.uHpxi');

CREATE TABLE `sessions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `session` VARCHAR(255) UNIQUE NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `expires_at` DATETIME NOT NULL
);

CREATE TABLE `stations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) UNIQUE NOT NULL,
    `key` VARCHAR(255) UNIQUE NOT NULL,
    `lat` DECIMAL(10, 8) NOT NULL,
    `lng` DECIMAL(11, 8) NOT NULL
);

CREATE TABLE `measurement` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED NOT NULL,
    `time` DATETIME NOT NULL,
    `temperature` DOUBLE NOT NULL,
    `humidity` DOUBLE NOT NULL,
    `light` DOUBLE NOT NULL
);
