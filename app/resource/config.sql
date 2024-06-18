CREATE DATABASE blog;

USE blog;

CREATE TABLE users (
                       id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE blogs (
                       id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                       author_email VARCHAR(255) NOT NULL,
                       title VARCHAR(255) NOT NULL,
                       content TEXT NOT NULL,
                       publish_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       FOREIGN KEY (author_email) REFERENCES users(email)
);

CREATE TABLE comments (
                          id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                          blog_id INT UNSIGNED NOT NULL,
                          author_email VARCHAR(255) NOT NULL,
                          commenter_email VARCHAR(255) NOT NULL,
                          content TEXT NOT NULL,
                          comment_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (blog_id) REFERENCES blogs(id),
                          FOREIGN KEY (author_email) REFERENCES users(email),
                          FOREIGN KEY (commenter_email) REFERENCES users(email)
);
