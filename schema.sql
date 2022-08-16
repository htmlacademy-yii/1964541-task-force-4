CREATE DATABASE task_force
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE task_force;

CREATE TABLE city (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      name CHAR(64) DEFAULT NULL,
                      lng FLOAT,
                      lat FLOAT
);

CREATE TABLE category (
                               id INT AUTO_INCREMENT PRIMARY KEY,
                               name VARCHAR(128) NOT NULL,
                               type VARCHAR(128) NOT NULL,
                               UNIQUE INDEX UI_type (type),
                               UNIQUE INDEX UI_name (name)
);

CREATE TABLE user (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     email VARCHAR(320) NOT NULL,
                     password CHAR(64) NOT NULL,
                     login VARCHAR(320) NOT NULL,
                     dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                     avatar TEXT DEFAULT NULL,
                     user_type ENUM ('customer', 'executor'),
                     rating INT NOT NULL,
                     city_id INT,
                     phone INT,
                     telegram CHAR(64),
                     FOREIGN KEY (city_id) REFERENCES city(id),
                     UNIQUE INDEX UI_email (email),
                     UNIQUE INDEX UI_login (login)
);

CREATE TABLE task (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     title VARCHAR(128) NOT NULL,
                     description TEXT DEFAULT NULL,
                     file VARCHAR(320) DEFAULT NULL,
                     lng FLOAT,
                     lat FLOAT,
                     city_id INT,
                     price INT NOT NULL,
                     customer_id INT NOT NULL,
                     executor_id INT,
                     status ENUM ('new', 'canceled', 'in_work', 'executed', 'failed'),
                     category_id INT NOT NULL,
                     deadline TIMESTAMP,
                     dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                     FOREIGN KEY (category_id) REFERENCES category(id),
                     FOREIGN KEY (executor_id) REFERENCES users (id),
                     FOREIGN KEY (customer_id) REFERENCES users (id),
                     FOREIGN KEY (city_id) REFERENCES city (id)
);

CREATE TABLE user_category (
                             user_id INT NOT NULL,
                             category_id INT NOT NULL,
                             FOREIGN KEY (category_id) REFERENCES category (id),
                             FOREIGN KEY (user_id) REFERENCES users (id),
                             PRIMARY KEY (user_id, category_id)
);

CREATE TABLE review (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        content TEXT NOT NULL,
                        user_id INT NOT NULL,
                        grade INT,
                        FOREIGN KEY (user_id) REFERENCES users (id),
                        task_id INT NOT NULL,
                        FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE response (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        task_id INT NOT NULL,
                        executor_id INT NOT NULL,
                        content TEXT NOT NULL,
                        price INT,
                        FOREIGN KEY (task_id) REFERENCES tasks (id),
                        FOREIGN KEY (executor_id) REFERENCES users (id)
);

