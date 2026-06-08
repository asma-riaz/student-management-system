-- 1. Create and select the database
CREATE DATABASE IF NOT EXISTS college_db;
USE college_db;

-- 2. Drop table if exists (fresh start)
DROP TABLE IF EXISTS students;

-- 3. Create students table
CREATE TABLE students (
    id    INT(11)      NOT NULL AUTO_INCREMENT,
    name  VARCHAR(100) NOT NULL,
    age   INT(3)       NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Insert 3 sample student records
INSERT INTO students (name, age, email,) VALUES
    ('Asma Riaz',   20, 'asmariaz2903@college.edu'),
    ('Sara Khan',    22, 'sarakhan@college.edu'),
    ('jannat Ali',  21, 'jannatali@college.edu');

-- 5. Verify
SELECT * FROM students;

ALTER TABLE students
ADD roll_no VARCHAR(20) UNIQUE,
ADD phone VARCHAR(20),
ADD department VARCHAR(100),
ADD semester INT,
ADD cgpa DECIMAL(3,2),
ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;