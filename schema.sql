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
    `text` VARCHAR(255) NOT NULL,
    `tag` VARCHAR(255) NOT NULL,
    `creation_date` DATETIME,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`)
    REFERENCES user(`id`)
);

