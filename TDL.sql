CREATE DATABASE kegiatan;
USE kegiatan;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE,
    status ENUM('Not started', 'In progress', 'Waiting on', 'Done') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
