CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(191) UNIQUE NOT NULL,
    `email` VARCHAR(191) UNIQUE NOT NULL,
    `password` VARCHAR(191) NOT NULL
);

INSERT INTO `users` (`username`, `email`, `password`) VALUES ('bplaat', 'bastiaan.v.d.plaat@gmail.com', '$2y$10$Nku/64sflag3AQ1uPv5PY.bGA8V8y14biZ2RWnfRsybqYjlzxUQ9a');

CREATE TABLE `sessions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `session` VARCHAR(191) UNIQUE NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `expires_at` DATETIME NOT NULL
);

CREATE TABLE `stations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(191) UNIQUE NOT NULL,
    `key` VARCHAR(191) UNIQUE NOT NULL,
    `lat` DECIMAL(10, 8) NOT NULL,
    `lng` DECIMAL(11, 8) NOT NULL
);

CREATE TABLE `measurements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED NOT NULL,
    `time` DATETIME NOT NULL,
    `temperature` DOUBLE NOT NULL,
    `humidity` INT UNSIGNED NOT NULL,
    `light` INT UNSIGNED NOT NULL
);
