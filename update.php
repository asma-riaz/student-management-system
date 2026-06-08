<?php

include 'db_connect.php';

$success  = "";
$error    = "";
$student  = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT roll_no, id, name, age, email, phone, department, semester, cgpa FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    if (!$student) {
        $error = " Student with ID $id not found.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = intval($_POST['id']);
    $roll_no    = trim($_POST['roll_no']);
    $name       = trim($_POST['name']);
    $age        = intval($_POST['age']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $department = trim($_POST['department']);
    $semester   = intval($_POST['semester']);
    $cgpa       = floatval($_POST['cgpa']);


    // Validation
    if (empty($age) || empty($email)) {
        $error = "Age and Email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($age < 15 || $age > 60) {
        $error = "Age must be between 15 and 60.";
    } else {
        // Prepared statement for UPDATE
        $stmt = $conn->prepare(" UPDATE students SET roll_no =?, name = ?, age = ?, email = ?, phone = ?, department = ?, semester = ?, cgpa = ? WHERE id = ?");
        if (!$stmt) {
    die($conn->error);
}

        $stmt->bind_param("ssisssidi", $roll_no, $name, $age, $email, $phone, $department, $semester, $cgpa, $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $success = " Record updated successfully!";
                // Re-fetch updated student to show in form
                $stmt2 = $conn->prepare("SELECT id, roll_no, name, age, email, phone, department, semester, cgpa FROM students WHERE id = ?");
                $stmt2->bind_param("i", $id);
                $stmt2->execute();
                $student = $stmt2->get_result()->fetch_assoc();
                $stmt2->close();
            } else {
                $success = " No changes were made (values are the same).";
            }
        } else {
            $error = ($stmt->errno === 1062)
                ? " Email already exists for another student."
                : " Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Student | College DB</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-pen-to-square"></i> Update Student Record</h1>
        <p>Modify the record of existing student.</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?> <a href="display.php">← Back to list</a></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($student): ?>
    <div class="card">
        <form method="POST" action="update.php?id=<?= $student['id'] ?>">

            <!-- Hidden ID field -->
            <input type="hidden" name="id" value="<?= $student['id'] ?>">

            <div class="form-group">
                <label>Student ID <span class="hint">(cannot be changed)</span> </label>
                <input type="text" value="#<?= $student['id'] ?>" disabled class="disabled-input">
            </div>

            <div class="form-group">
                <label for="roll_no">Roll Number</label>
                <input type="text" id="roll_no" name="roll_no" value="<?= htmlspecialchars($student['roll_no']) ?>" required>
            </div>

            <div class="form-group">
                <label>Name </label>
                <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>">
            </div>

            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" min="15" max="60" value="<?= htmlspecialchars($student['age']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" required>
            </div>

            <div class="form-group">
                <label for="department">Department</label>
                <select name="department" id="department" required>
                    <option value="">Select Department</option>

                    <option value="Computer Science" <?= $student['department'] == 'Computer Science' ? 'selected' : '' ?>>
                    Computer Science
                    </option>

                    <option value="Software Engineering" <?= $student['department'] == 'Software Engineering' ? 'selected' : '' ?>>
                    Software Engineering
                    </option>

                    <option value="Information Technology" <?= $student['department'] == 'Information Technology' ? 'selected' : '' ?>>
                    Information Technology
                    </option>

                    <option value="Artificial Intelligence" <?= $student['department'] == 'Artificial Intelligence' ? 'selected' : '' ?>>
                    Artificial Intelligence
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="semester">Semester</label>
                <input type="number" id="semester" name="semester" min="1" max="8" value="<?= htmlspecialchars($student['semester']) ?>" required>
            </div>

            <div class="form-group">
                <label for="cgpa">CGPA</label>
                <input type="number" id="cgpa" name="cgpa" step="0.01" min="0" max="4" value="<?= htmlspecialchars($student['cgpa']) ?>" required>
            </div>


            <div class="form-actions">
                <button type="submit" class="btn btn-warning"><i class="fa-regular fa-floppy-disk"></i> Save Changes</button>
                <a href="display.php" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i> Cancel</a>
            </div>

        </form>
    </div>

    <?php elseif (!$error): ?>
        <!-- No ID passed — show select form -->
        <div class="card">
            <p>Enter the student ID to update:</p>
            <form method="GET" action="update.php">
                <div class="form-group">
                    <label for="id">Student ID</label>
                    <input type="number" id="id" name="id" placeholder="e.g. 1" min="1" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-warning"><i class="fa-solid fa-magnifying-glass"></i> Find Student</button>
                    <a href="display.php" class="btn btn-secondary"><i class="fa-solid fa-book-open"></i> View All</a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>


