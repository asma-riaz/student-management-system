<?php

include 'db_connect.php';

// Fetch all students grouped by department then semester
$result = $conn->query("
    SELECT id, roll_no, name, age, email, phone, department, semester, cgpa
    FROM students
    ORDER BY department ASC, semester ASC, name ASC
");

$departments = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dept = $row['department'] ?: 'Unassigned';
        $sem  = $row['semester']   ?: 0;
        $departments[$dept][$sem][] = $row;
    }
}

$conn->close();

// Department color map (badge accent)
$deptColors = [
    'Computer Science'       => '#2563eb',
    'Software Engineering'   => '#7c3aed',
    'Information Technology' => '#0891b2',
    'Artificial Intelligence'=> '#059669',
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Departments | College DB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>

        /* Filter bar */
        .dept-filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 24px;
            align-items: center;
        }

        .dept-filter-bar .filter-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-right: 4px;
        }

        .filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 20px;
            border: 2px solid #e2e8f0;
            background: #fff;
            font-size: 0.83rem;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
            transition: all 0.18s;
        }

        .filter-btn:hover {
            border-color: #94a3b8;
            color: #1e293b;
        }

        .filter-btn.active {
            color: #fff;
            border-color: transparent;
        }

        .filter-btn .filter-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .filter-btn.active .filter-dot {
            background: rgba(255,255,255,0.6);
        }

        /* Department block */
        .dept-block {
            margin-bottom: 28px;
            border-radius: 14px;
            overflow: hidden;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        /* Department header (clickable) */
        .dept-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 24px;
            background: #1e3a5f;
            cursor: pointer;
            user-select: none;
            transition: background 0.2s;
        }

        .dept-header:hover { background: #16304f; }

        .dept-header .dept-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .dept-header .dept-title {
            flex: 1;
        }

        .dept-header .dept-title h2 {
            color: #fff;
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
        }

        .dept-header .dept-title span {
            font-size: 0.78rem;
            color: #94a3b8;
            margin-top: 2px;
            display: block;
        }

        .dept-header .dept-badge {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .dept-header .toggle-icon {
            color: #94a3b8;
            font-size: 0.95rem;
            transition: transform 0.3s;
        }

        .dept-header.collapsed .toggle-icon {
            transform: rotate(-90deg);
        }

        /* Department body */
        .dept-body {
            background: #f8fafc;
            padding: 20px 24px;
            display: block;
        }

        .dept-body.hidden { display: none; }

        /* Semester block */
        .sem-block {
            margin-bottom: 18px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            background: #fff;
        }

        .sem-block:last-child { margin-bottom: 0; }

        .sem-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            background: #f1f5f9;
            cursor: pointer;
            user-select: none;
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.15s;
        }

        .sem-header:hover { background: #e8edf4; }

        .sem-pill {
            border-radius: 6px;
            padding: 3px 12px;
            font-size: 0.78rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.04em;
        }

        .sem-header .sem-label {
            flex: 1;
            font-size: 0.88rem;
            font-weight: 700;
            color: #334155;
        }

        .sem-header .sem-count {
            font-size: 0.78rem;
            color: #64748b;
        }

        .sem-header .sem-toggle {
            color: #94a3b8;
            font-size: 0.82rem;
            transition: transform 0.25s;
        }

        .sem-header.collapsed .sem-toggle {
            transform: rotate(-90deg);
        }

        /* Semester table body */
        .sem-body {
            padding: 0;
            display: block;
        }

        .sem-body.hidden { display: none; }

        .sem-body .table-wrap { border: none; border-radius: 0; }

        .sem-body table { font-size: 0.87rem; }

        .sem-body thead th { font-size: 0.76rem; padding: 10px 14px; }

        .sem-body tbody td { padding: 10px 14px; }

        /* Stats bar at bottom of each dept */
        .dept-stats {
            display: flex;
            gap: 16px;
            padding: 14px 24px;
            background: #fff;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }

        .dept-stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 80px;
        }

        .dept-stat-item .stat-val {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1e3a5f;
        }

        .dept-stat-item .stat-lbl {
            font-size: 0.7rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 2px;
        }

        .stat-sep {
            width: 1px;
            background: #e2e8f0;
            align-self: stretch;
            margin: 4px 0;
        }

        /* Empty state */
        .empty-dept {
            padding: 40px;
            text-align: center;
            color: #94a3b8;
        }

        /* CGPA color coding */
        .cgpa-high   { color: #16a34a; font-weight: 700; }
        .cgpa-mid    { color: #d97706; font-weight: 700; }
        .cgpa-low    { color: #dc2626; font-weight: 700; }

        @media (max-width: 640px) {
            .dept-header, .dept-body { padding: 14px 16px; }
            .dept-stats { padding: 12px 16px; }
        }

    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <div class="page-header">
        <h1><i class="fa-solid fa-building-columns"></i> Department Overview</h1>
        <p>Browse students by department and semester. Click any section to expand or collapse it.</p>
    </div>

    <?php if (empty($departments)): ?>
        <div class="card empty-dept">
            <i class="fa-solid fa-circle-info" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
            <p>No student records found. <a href="insert.php">Add the first student.</a></p>
        </div>

    <?php else: ?>

    <div class="dept-filter-bar">
        <span class="filter-label"><i class="fa-solid fa-filter"></i> Filter</span>

        <button class="filter-btn active" style="background:#1e3a5f; border-color:#1e3a5f;" onclick="filterDept('all', this)">
            <span class="filter-dot" style="background:rgba(255,255,255,0.5);"></span>
            All Departments
        </button>

        <?php foreach (array_keys($departments) as $dn):
            $c = $deptColors[$dn] ?? '#475569';
        ?>
        <button class="filter-btn"
                data-dept="<?= htmlspecialchars($dn) ?>"
                onclick="filterDept('<?= htmlspecialchars(addslashes($dn)) ?>', this)"
                style="--dept-color:<?= $c ?>;">
            <span class="filter-dot" style="background:<?= $c ?>;"></span>
            <?= htmlspecialchars($dn) ?>
        </button>
        <?php endforeach; ?>
    </div>

        <?php foreach ($departments as $deptName => $semesters): ?>

            <?php
                $color       = $deptColors[$deptName] ?? '#475569';
                $totalInDept = 0;
                $cgpaSum     = 0;
                $cgpaCount   = 0;
                $semCount    = count($semesters);
                foreach ($semesters as $semStudents) {
                    foreach ($semStudents as $s) {
                        $totalInDept++;
                        if ($s['cgpa'] > 0) { $cgpaSum += $s['cgpa']; $cgpaCount++; }
                    }
                }
                $avgCgpa = $cgpaCount ? round($cgpaSum / $cgpaCount, 2) : 'N/A';

                // Icon per department
                $icons = [
                    'Computer Science'        => 'fa-solid fa-computer',
                    'Software Engineering'    => 'fa-solid fa-code',
                    'Information Technology'  => 'fa-solid fa-network-wired',
                    'Artificial Intelligence' => 'fa-solid fa-robot',
                ];
                $icon = $icons[$deptName] ?? 'fa-solid fa-graduation-cap';
            ?>

            <div class="dept-block" data-dept="<?= htmlspecialchars($deptName) ?>">

                <div class="dept-header" onclick="toggleSection(this, 'dept-<?= md5($deptName) ?>')">
                    <div class="dept-icon" style="background:<?= $color ?>;">
                        <i class="<?= $icon ?>"></i>
                    </div>
                    <div class="dept-title">
                        <h2><?= htmlspecialchars($deptName) ?></h2>
                        <span><?= $semCount ?> semester<?= $semCount !== 1 ? 's' : '' ?> active</span>
                    </div>
                    <span class="dept-badge"><?= $totalInDept ?> student<?= $totalInDept !== 1 ? 's' : '' ?></span>
                    <i class="fa-solid fa-chevron-down toggle-icon"></i>
                </div>

                <div class="dept-body" id="dept-<?= md5($deptName) ?>">

                    <?php
                    ksort($semesters); // sort semesters numerically
                    foreach ($semesters as $semNum => $students):
                        $semLabel   = $semNum ? "Semester $semNum" : "Unassigned Semester";
                        $semId      = 'sem-' . md5($deptName) . '-' . $semNum;
                        $stuCount   = count($students);
                    ?>

                    <div class="sem-block">

                        <div class="sem-header" onclick="toggleSection(this, '<?= $semId ?>')">
                            <span class="sem-pill" style="background:<?= $color ?>;"><?= $semNum ?: 'N/A' ?></span>
                            <span class="sem-label"><?= $semLabel ?></span>
                            <span class="sem-count"><?= $stuCount ?> student<?= $stuCount !== 1 ? 's' : '' ?></span>
                            <i class="fa-solid fa-chevron-down sem-toggle"></i>
                        </div>

                        <div class="sem-body" id="<?= $semId ?>">
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Roll No</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>CGPA</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $s): ?>
                                        <?php
                                            $cgpaClass = '';
                                            if ($s['cgpa'] >= 3.5)       $cgpaClass = 'cgpa-high';
                                            elseif ($s['cgpa'] >= 2.5)   $cgpaClass = 'cgpa-mid';
                                            else                          $cgpaClass = 'cgpa-low';
                                        ?>
                                        <tr>
                                            <td><?= (int)$s['id'] ?></td>
                                            <td><?= htmlspecialchars($s['roll_no']) ?></td>
                                            <td><?= htmlspecialchars($s['name']) ?></td>
                                            <td><?= (int)$s['age'] ?></td>
                                            <td><?= htmlspecialchars($s['email']) ?></td>
                                            <td><?= htmlspecialchars($s['phone']) ?></td>
                                            <td class="<?= $cgpaClass ?>"><?= $s['cgpa'] ?></td>
                                            <td class="action-btns">
                                                <a href="update.php?id=<?= $s['id'] ?>" class="btn btn-warning btn-sm">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </a>
                                                <a href="delete.php?id=<?= $s['id'] ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Delete this student?')">
                                                    <i class="fa-regular fa-rectangle-xmark"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div><!-- .sem-block -->

                    <?php endforeach; ?>

                </div><!-- .dept-body -->

                <!-- Department summary bar -->
                <div class="dept-stats">
                    <div class="dept-stat-item">
                        <span class="stat-val"><?= $totalInDept ?></span>
                        <span class="stat-lbl">Total Students</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="dept-stat-item">
                        <span class="stat-val"><?= $semCount ?></span>
                        <span class="stat-lbl">Semesters</span>
                    </div>
                    <div class="stat-sep"></div>
                    <div class="dept-stat-item">
                        <span class="stat-val"><?= $avgCgpa ?></span>
                        <span class="stat-lbl">Avg CGPA</span>
                    </div>
                </div>

            </div><!-- .dept-block -->

        <?php endforeach; ?>

    <?php endif; ?>

    <div class="form-actions" style="margin-top: 24px;">
        <a href="insert.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Student</a>
        <a href="display.php" class="btn btn-secondary"><i class="fa-solid fa-book-open"></i> View All Records</a>
    </div>

</div>

<script>
    function toggleSection(headerEl, bodyId) {
        const body = document.getElementById(bodyId);
        const isHidden = body.classList.toggle('hidden');
        headerEl.classList.toggle('collapsed', isHidden);
    }

    function filterDept(value, clickedBtn) {
        // Update active button styles
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.style.background = '';
            btn.style.borderColor = '';
            btn.style.color = '';
        });

        clickedBtn.classList.add('active');

        if (value === 'all') {
            clickedBtn.style.background = '#1e3a5f';
            clickedBtn.style.borderColor = '#1e3a5f';
            clickedBtn.style.color = '#fff';
        } else {
            const color = getComputedStyle(clickedBtn).getPropertyValue('--dept-color').trim();
            clickedBtn.style.background = color;
            clickedBtn.style.borderColor = color;
            clickedBtn.style.color = '#fff';
        }

        // Show or hide dept blocks
        document.querySelectorAll('.dept-block').forEach(block => {
            if (value === 'all' || block.dataset.dept === value) {
                block.style.display = '';
            } else {
                block.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>