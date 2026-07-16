<?php

include 'db_connect.php';

// ---------------- BASIC METRICS ----------------

// Total Students
$totalStudents = $conn->query(
    "SELECT COUNT(*) AS total FROM students"
)->fetch_assoc()['total'] ?? 0;

// Average CGPA
$avgCgpa = $conn->query(
    "SELECT AVG(cgpa) AS avg_cgpa FROM students"
)->fetch_assoc()['avg_cgpa'] ?? 0;

// Latest Student
$latestStudent = $conn->query(
    "SELECT name FROM students ORDER BY id DESC LIMIT 1"
)->fetch_assoc();

// ---------------- PER DEPARTMENT / PER SEMESTER STATS ----------------
$semStats = $conn->query(
    "SELECT department, semester, COUNT(*) AS total, AVG(cgpa) AS avg_cgpa
     FROM students
     WHERE department IS NOT NULL AND department != ''
     GROUP BY department, semester
     ORDER BY department ASC, semester ASC"
);

$deptSemStats = [];

if ($semStats && $semStats->num_rows > 0) {
    while ($row = $semStats->fetch_assoc()) {
        $dept = $row['department'];
        $sem  = (int)$row['semester'];
        $deptSemStats[$dept][$sem] = [
            'total'    => (int)$row['total'],
            'avg_cgpa' => (float)$row['avg_cgpa'],
        ];
    }
}

$conn->close();

// Keep departments in a sensible, consistent order; unknown ones fall in after, alphabetically.
$deptOrder = ['Computer Science', 'Software Engineering', 'Information Technology', 'Artificial Intelligence'];
$knownDepts  = array_values(array_intersect($deptOrder, array_keys($deptSemStats)));
$otherDepts  = array_values(array_diff(array_keys($deptSemStats), $deptOrder));
sort($otherDepts);
$orderedDepts = array_merge($knownDepts, $otherDepts);

// Shared visual language with departments.php
$deptColors = [
    'Computer Science'        => '#2563eb',
    'Software Engineering'    => '#7c3aed',
    'Information Technology'  => '#0891b2',
    'Artificial Intelligence' => '#059669',
];

$deptIcons = [
    'Computer Science'        => 'fa-solid fa-computer',
    'Software Engineering'    => 'fa-solid fa-code',
    'Information Technology'  => 'fa-solid fa-network-wired',
    'Artificial Intelligence' => 'fa-solid fa-robot',
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">

    <style>

        .dept-dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .dept-summary-card {
            padding: 0;
            overflow: hidden;
        }

        .dept-summary-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 22px;
            border-bottom: 1px solid #e2e8f0;
        }

        .dept-summary-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: #fff;
            flex-shrink: 0;
        }

        .dept-summary-title {
            flex: 1;
        }

        .dept-summary-title h3 {
            font-size: 1.02rem;
            font-weight: 700;
            color: #1e3a5f;
        }

        .dept-summary-badge {
            background: #f1f5f9;
            color: #1e3a5f;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .dept-sem-table {
            width: 100%;
        }

        .dept-sem-table thead th {
            font-size: 0.72rem;
            padding: 10px 22px;
        }

        .dept-sem-table tbody td {
            padding: 9px 22px;
            font-size: 0.88rem;
        }

        .cgpa-high { color: #16a34a; font-weight: 700; }
        .cgpa-mid  { color: #d97706; font-weight: 700; }
        .cgpa-low  { color: #dc2626; font-weight: 700; }
        .cgpa-none { color: #94a3b8; }

    </style>
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <div class="page-header">
        <h1><i class="fa-solid fa-chart-line"></i> Dashboard</h1>
        <p>Student Management Overview</p>
    </div>

    <!-- ---------------- MAIN METRICS ---------------- -->
    <div class="dashboard-grid">

        <div class="card">
            <h2><?= (int)$totalStudents ?></h2>
            <p>Total Students</p>
        </div>

        <div class="card">
            <h2><?= round((float)$avgCgpa, 2) ?></h2>
            <p>Average CGPA</p>
        </div>

    </div>

    <!-- ---------------- DEPARTMENT CARDS ---------------- -->
    <div class="page-header" style="margin-top:32px; margin-bottom:0;">
        <h1 style="font-size:1.3rem;"><i class="fa-solid fa-building-columns"></i> Departments</h1>
        <p>Students and average CGPA per semester, for each department.</p>
    </div>

    <?php if (empty($orderedDepts)): ?>

        <div class="card" style="margin-top:20px;">
            <p>No department data found.</p>
        </div>

    <?php else: ?>

    <div class="dept-dashboard-grid">

        <?php foreach ($orderedDepts as $deptName):
            $color   = $deptColors[$deptName] ?? '#475569';
            $icon    = $deptIcons[$deptName]  ?? 'fa-solid fa-graduation-cap';
            $semData = $deptSemStats[$deptName];
            $deptTotal = array_sum(array_column($semData, 'total'));
        ?>

        <div class="card dept-summary-card">

            <div class="dept-summary-header" style="border-top:4px solid <?= $color ?>;">
                <div class="dept-summary-icon" style="background:<?= $color ?>;">
                    <i class="<?= $icon ?>"></i>
                </div>
                <div class="dept-summary-title">
                    <h3><?= htmlspecialchars($deptName) ?></h3>
                </div>
                <span class="dept-summary-badge"><?= $deptTotal ?> student<?= $deptTotal !== 1 ? 's' : '' ?></span>
            </div>

            <div class="table-wrap" style="border:none; border-radius:0;">
                <table class="dept-sem-table">
                    <thead>
                        <tr>
                            <th>Semester</th>
                            <th>Students</th>
                            <th>Avg CGPA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($sem = 1; $sem <= 8; $sem++):
                            $stats   = $semData[$sem] ?? null;
                            $count   = $stats['total']    ?? 0;
                            $avg     = $stats['avg_cgpa']  ?? null;

                            $cgpaClass = 'cgpa-none';
                            $avgLabel  = '—';
                            if ($avg !== null) {
                                $avgLabel = number_format($avg, 2);
                                if ($avg >= 3.5)      $cgpaClass = 'cgpa-high';
                                elseif ($avg >= 2.5)  $cgpaClass = 'cgpa-mid';
                                else                   $cgpaClass = 'cgpa-low';
                            }
                        ?>
                        <tr>
                            <td>Semester <?= $sem ?></td>
                            <td><?= $count ?></td>
                            <td class="<?= $cgpaClass ?>"><?= $avgLabel ?></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

        </div><!-- .dept-summary-card -->

        <?php endforeach; ?>

    </div><!-- .dept-dashboard-grid -->

    <?php endif; ?>

</div>

</body>
</html>