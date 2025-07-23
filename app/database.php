<?php

/* database connection stuff here
 * 
 */

function db_connect() {
    try { 
        $dbh = new PDO('mysql:host=' . DB_HOST . ';port='. DB_PORT . ';dbname=' . DB_DATABASE, DB_USER, DB_PASS);
        return $dbh;
    } catch (PDOException $e) {
        //We should set a global variable here so we know the DB is down
    }
}

/*


DB Scripts
CREATE TABLE `mv_search_logs` ( 
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_id` INT NULL DEFAULT NULL ,
  `movie_title` VARCHAR(255) NOT NULL,
  `search_time` TIMESTAMP NULL DEFAULT current_timestamp() ,
  `created_at` TIMESTAMP NULL DEFAULT current_timestamp() ,
  CONSTRAINT `PRIMARY` PRIMARY KEY (`id`)
);
CREATE TABLE `mv_ratings` ( 
  `id` INT AUTO_INCREMENT NOT NULL,
  `movie_title` VARCHAR(255) NOT NULL,
  `rating` INT NULL DEFAULT NULL ,
  `user_id` INT NULL DEFAULT NULL ,
  `created_at` TIMESTAMP NULL DEFAULT current_timestamp() ,
  CONSTRAINT `PRIMARY` PRIMARY KEY (`id`)
);
CREATE TABLE `mv_reviews` ( 
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_id` INT NOT NULL,
  `movie_title` VARCHAR(255) NOT NULL,
  `rating` INT NULL DEFAULT NULL ,
  `created_at` TIMESTAMP NULL DEFAULT current_timestamp() ,
  CONSTRAINT `PRIMARY` PRIMARY KEY (`id`)
);
CREATE TABLE `mv_users` ( 
  `id` INT AUTO_INCREMENT NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','user') NULL DEFAULT 'user' ,
  `created_at` TIMESTAMP NULL DEFAULT current_timestamp() ,
  CONSTRAINT `PRIMARY` PRIMARY KEY (`id`),
  CONSTRAINT `username` UNIQUE (`username`)
);
CREATE TABLE `mv_login_logs` ( 
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_id` INT NOT NULL,
  `login_time` TIMESTAMP NULL DEFAULT current_timestamp() ,
  CONSTRAINT `PRIMARY` PRIMARY KEY (`id`)
);
CREATE INDEX `user_id` 
ON `mv_search_logs` (
  `user_id` ASC
);
CREATE INDEX `user_id` 
ON `mv_ratings` (
  `user_id` ASC
);
CREATE INDEX `user_id` 
ON `mv_login_logs` (
  `user_id` ASC
);
ALTER TABLE `mv_search_logs` ADD CONSTRAINT `mv_search_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `mv_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
ALTER TABLE `mv_ratings` ADD CONSTRAINT `mv_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `mv_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
ALTER TABLE `mv_login_logs` ADD CONSTRAINT `mv_login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `mv_users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;


INSERT INTO mv_users (username, password_hash, role)
VALUES ('admin', SHA2('admin', 256), 'admin');
*/