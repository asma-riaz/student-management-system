# Student Management System

A full-stack web application built with **PHP**, **MySQL**, and **Vanilla CSS** for managing student records across departments and semesters.

---

## Tech Stack

| Layer      | Technology              |
|------------|-------------------------|
| Frontend   | HTML, CSS, JavaScript   |
| Backend    | PHP (procedural)        |
| Database   | MySQL                   |
| Server     | XAMPP (Apache + MySQL)  |
| Icons      | Font Awesome 7          |

---

## Features

- Add, view, edit, and delete student records (full CRUD)
- Search students by name, email, or roll number
- Department Overview page with collapsible sections per department and per semester
- Filter bar to instantly show one department at a time
- CGPA color coding (green / amber / red)
- Dashboard with total students, average age, average CGPA, and department distribution
- Active navbar link highlighting on every page
- Fully responsive layout (mobile friendly)
- Prepared statements throughout — no raw SQL injection risk

---

## Project Structure

```
student-management/
├── db_connect.php      # Database connection (MySQLi)
├── dashboard.php       # Stats overview page
├── display.php         # View all students with search
├── departments.php     # Department and semester breakdown
├── insert.php          # Add new student form
├── update.php          # Edit existing student form
├── delete.php          # Delete confirmation flow
├── navbar.php          # Shared navigation included on all pages
├── style.css           # Global stylesheet
└── students.sql        # Database schema and sample data
```

---

## Database Setup

### 1. Start XAMPP
Open XAMPP Control Panel and start **Apache** and **MySQL**.

### 2. Import the database
Open your browser and go to:
```
http://localhost/phpmyadmin
```
Click **Import**, select `students.sql`, and click **Go**.

This will create the `college_db` database with the `students` table and sample records.

### 3. Verify connection settings
Open `db_connect.php` and confirm these match your XAMPP setup:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // empty by default in XAMPP
define('DB_NAME', 'college_db');
```

---

## Running the Project

1. Copy the entire project folder into your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\student-management\
```

2. Open your browser and go to:
```
http://localhost/student-management/dashboard.php
```

---

## Pages

| Page               | URL                   | Description                              |
|--------------------|-----------------------|------------------------------------------|
| Dashboard          | `dashboard.php`       | Key metrics and department stats         |
| View All Records   | `display.php`         | Full student table with search           |
| Department View    | `departments.php`     | Students grouped by department/semester  |
| Insert Student     | `insert.php`          | Add a new student                        |
| Update Student     | `update.php?id=X`     | Edit an existing student by ID           |
| Delete Student     | `delete.php?id=X`     | Confirm and delete a student by ID       |

---

## Student Fields

| Field        | Type            | Validation                        |
|--------------|-----------------|-----------------------------------|
| Roll No      | VARCHAR(20)     | Required, unique                  |
| Name         | VARCHAR(100)    | Required                          |
| Age          | INT             | Required, between 15 and 60       |
| Email        | VARCHAR(150)    | Required, unique, valid format    |
| Phone        | VARCHAR(20)     | Required                          |
| Department   | VARCHAR(100)    | CS / SE / IT / AI                 |
| Semester     | INT             | Between 1 and 8                   |
| CGPA         | DECIMAL(3,2)    | Between 0.00 and 4.00             |

---

## Author

**Asma Riaz**
GitHub: [github.com/asma-riaz](https://github.com/asma-riaz)