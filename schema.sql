CREATE DATABASE Tweeter CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `user` (
    `id` INT AUTO_INCREMENT,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `username` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
     PRIMARY KEY (`id`)
);

CREATE TABLE `tweet` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `text` VARCHAR(140) NOT NULL,
    `tag` VARCHAR(255) NOT NULL,
    `creation_date` DATETIME,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`)
    REFERENCES user(`id`)
);

CREATE TABLE `comment` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `tweet_id` INT NOT NULL,
    `text` VARCHAR(60) NOT NULL,
    `creation_date` DATETIME,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES user(`id`),
    FOREIGN KEY (`tweet_id`) REFERENCES tweet(`id`)
);

CREATE TABLE `message` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `sender_id` INT NOT NULL,
    `receiver_id` INT NOT NULL,
    `text` VARCHAR(140) NOT NULL,
    `creation_date` DATETIME,
    `is_read` TINYINT(1),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`sender_id`) REFERENCES user(`id`),
    FOREIGN KEY (`receiver_id`) REFERENCES user(`id`)
);
