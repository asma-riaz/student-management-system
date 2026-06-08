<?php

include(__DIR__ . '/db_connect.php');

$search = $_GET['search'] ?? '';

if ($search !== '') {

    if (is_numeric($search)) {

        $stmt = $conn->prepare(" SELECT id, roll_no, name, age, email, phone, department, semester, cgpa
            FROM students
            WHERE id = ? OR roll_no = ?
            ORDER BY id ASC
        ");

        $stmt->bind_param("is", $search, $search);

    } else {

        $stmt = $conn->prepare(" SELECT id, roll_no, name, age, email, phone, department, semester, cgpa
            FROM students
            WHERE name LIKE ? OR email LIKE ? OR roll_no LIKE ?
            ORDER BY id ASC
        ");

        $keyword = "%$search%";
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    }

    $stmt->execute();
    $result = $stmt->get_result();
}else{

    $result = $conn->query(
        "SELECT id, roll_no, name, age, email, phone, department, semester, cgpa
         FROM students
         ORDER BY id ASC"
    );
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Students | College DB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-book-open"></i> All Student Records</h1>
        <p>Showing all students from </p>
    </div>


<form method="GET" class="search-form">

    <input
        type="text"
        name="search"
        placeholder="Search by name or email..."
        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
    >

    <button type="submit" class="btn btn-primary">
        Search
    </button>

</form>



    <div class="card">
        <?php if ($result && $result->num_rows > 0): ?>

            <p class="record-count">Total Records: <strong><?= $result->num_rows ?></strong></p>

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
                            <th>Department</th>
                            <th>Semester</th>
                            <th>CGPA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['roll_no']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= $row['age'] ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['department']) ?></td>
                            <td><?= $row['semester'] ?></td>
                            <td><?= $row['cgpa'] ?></td>
                            <td class="action-btns">
                                <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this student?')"> <i class="fa-regular fa-rectangle-xmark"></i>Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <p class="no-records">No student records found. <a href="insert.php">Insert one now.</a></p>
        <?php endif; ?>
    </div>

    <div class="form-actions">
        <a href="insert.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Student</a>
    </div>
</div>

</body>
</html>
