CREATE DATABASE taskForce
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE taskFotce;

CREATE TABLE users (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     email VARCHAR(320) NOT NULL,
                     password CHAR(64) NOT NULL,
                     login VARCHAR(320) NOT NULL,
                     dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                     avatar TEXT DEFAULT NULL,
                     user_type ENUM ('customer', 'executor'),
                     rating INT NOT NULL,
                     city_id INT, #Возможно лучше сделать location с кордами, а не id города
                     phone INT NOT NULL,
                     telegram CHAR(64),
                     task_category_id INT, #Может быть NULL у заказчиков
                     FOREIGN KEY (city_id) REFERENCES cities(id),
                     FOREIGN KEY (task_category_id) REFERENCES task_categories(id),
                     UNIQUE INDEX UI_email (email),
                     UNIQUE INDEX UI_login (login)
);

CREATE TABLE task_categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(128) NOT NULL,
                            type VARCHAR(128) NOT NULL,
                            UNIQUE INDEX UI_type (type)
);

CREATE TABLE tasks (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     title VARCHAR(128) NOT NULL,
                     description TEXT DEFAULT NULL,
                     file VARCHAR(320) DEFAULT NULL,
                     location INT NOT NULL,
                     price INT NOT NULL,
                     customer_id INT NOT NULL,
                     executor_id INT NOT NULL,
                     status CHAR(64) NOT NULL,
                     task_category_id INT NOT NULL,
                     deadline TIMESTAMP,
                     dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                     FOREIGN KEY (task_category_id) REFERENCES task_categories(id),
                     FOREIGN KEY (executor_id) REFERENCES users (id),
                     FOREIGN KEY (customer_id) REFERENCES users (id)
);

CREATE TABLE cities (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name CHAR(64) DEFAULT NULL,
                    location INT NOT NULL,
                    UNIQUE INDEX UI_city (name)
);

CREATE TABLE reviews (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        content TEXT NOT NULL,
                        user_id INT NOT NULL,
                        grade INT,
                        FOREIGN KEY (user_id) REFERENCES users (id),
                        task_id INT NOT NULL,
                        FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE responses (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        task_id INT NOT NULL,
                        executor_id INT NOT NULL,
                        content TEXT NOT NULL,
                        price INT,
                        FOREIGN KEY (task_id) REFERENCES tasks (id),
                        FOREIGN KEY (executor_id) REFERENCES users (id)
);

