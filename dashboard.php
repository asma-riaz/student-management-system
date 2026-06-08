<?php

include 'db_connect.php';

// ---------------- BASIC METRICS ----------------

// Total Students
$totalStudents = $conn->query(
    "SELECT COUNT(*) AS total FROM students"
)->fetch_assoc()['total'] ?? 0;

// Average Age
$avgAge = $conn->query(
    "SELECT AVG(age) AS avg_age FROM students"
)->fetch_assoc()['avg_age'] ?? 0;

// Average CGPA (NEW - important missing metric)
$avgCgpa = $conn->query(
    "SELECT AVG(cgpa) AS avg_cgpa FROM students"
)->fetch_assoc()['avg_cgpa'] ?? 0;

// Latest Student
$latestStudent = $conn->query(
    "SELECT name FROM students ORDER BY id DESC LIMIT 1"
)->fetch_assoc();

// ---------------- DEPARTMENT STATS ----------------
$deptStats = $conn->query(
    "SELECT department, COUNT(*) AS total
     FROM students
     GROUP BY department
     ORDER BY total DESC"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <div class="page-header">
        <h1> Dashboard</h1>
        <p>Student Management Overview</p>
    </div>

    <!-- ---------------- MAIN METRICS ---------------- -->
    <div class="dashboard-grid">

        <div class="card">
            <h2><?= (int)$totalStudents ?></h2>
            <p>Total Students</p>
        </div>

        <div class="card">
            <h2><?= round((float)$avgAge, 1) ?></h2>
            <p>Average Age</p>
        </div>

        <div class="card">
            <h2><?= round((float)$avgCgpa, 2) ?></h2>
            <p>Average CGPA</p>
        </div>

    </div>

    <!-- ---------------- DEPARTMENT BREAKDOWN ---------------- -->
    <div class="card" style="margin-top:20px;">

        <h2>📚 Department Distribution</h2>

        <?php if ($deptStats && $deptStats->num_rows > 0): ?>
            <table style="width:100%; margin-top:10px;">
                <tr>
                    <th>Department</th>
                    <th>Total Students</th>
                </tr>

                <?php while ($row = $deptStats->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['department']) ?></td>
                        <td><?= (int)$row['total'] ?></td>
                    </tr>
                <?php endwhile; ?>

            </table>
        <?php else: ?>
            <p>No department data found.</p>
        <?php endif; ?>

    </div>

</div>

</body>
</html>