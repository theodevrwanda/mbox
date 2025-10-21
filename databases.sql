----------- project 2024 ---------------------------------
/*
database name : mbox
table movies,users,myusers,feedback
*/
-- movies table query bellow
CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    relationship VARCHAR(255),
    description TEXT,
    category VARCHAR(100) DEFAULT 'other',
    translator VARCHAR(255),
    poster_link VARCHAR(255),
    trail_url VARCHAR(255),
    download_link VARCHAR(255),
    stts ENUM('popular', 'none') DEFAULT 'none',
    data_uploaded TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
--- feedback table query 
CREATE TABLE feedback (
    feed_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    feedback TEXT NOT NULL,
    date_submitted DATETIME DEFAULT CURRENT_TIMESTAMP
);
-- users table query 
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);
--myusers table query 
CREATE TABLE myusers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
--- end query 
-- online db info
