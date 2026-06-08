<?php

include 'db_connect.php';

$success = "";
$error   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $roll_no = trim($_POST['roll_no']);
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = trim($_POST['department']);
    $semester = intval($_POST['semester']);
    $cgpa = floatval($_POST['cgpa']);

    if (
        empty($roll_no) || empty($name) || empty($age) ||
        empty($email) || empty($phone) || empty($department) ||
        empty($semester) || empty($cgpa)
    ) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($age < 15 || $age > 60) {
        $error = "Age must be between 15 and 60.";
    } elseif ($semester < 1 || $semester > 8) {
        $error = "Semester must be between 1 and 8.";
    } elseif ($cgpa < 0 || $cgpa > 4) {
        $error = "CGPA must be between 0 and 4.";
    }

    if (empty($error)) {

        $stmt = $conn->prepare("SELECT id FROM students WHERE roll_no = ?");
        $stmt->bind_param("s", $roll_no);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Roll number already exists.";
        }
        $stmt->close();
    }

    if (empty($error)) {

        $stmt = $conn->prepare("
            INSERT INTO students
            (roll_no, name, age, email, phone, department, semester, cgpa)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssisssid",
            $roll_no,
            $name,
            $age,
            $email,
            $phone,
            $department,
            $semester,
            $cgpa
        );

        if ($stmt->execute()) {
            $success = "Student <strong>" . htmlspecialchars($name) . "</strong> inserted successfully! (ID: " . $conn->insert_id . ")";
        } else {
            $error = ($stmt->errno === 1062)
                ? "Email already exists."
                : "Error: " . $stmt->error;
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
    <title>Insert Student | College DB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-plus"></i> Insert Student</h1>
        <p>Add a new student record to the database.</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="insert.php">

            <div class="form-group">
                <label for="roll_no">Roll No</label>
                <input type="number" id="rollno" name="roll_no"  value="<?=isset($_POST['roll_no']) ? (int)$_POST['roll_no'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="name"> Name</label>
                <input type="text" id="name" name="name" value=" <?= isset($_POST['name']) ?htmlspecialchars($_POST['name']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" min="15" max="60" value="<?=isset($_POST['age']) ? (int)$_POST['age'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?=isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" required>
            </div>

            <div class="form-group">
                <label>Department</label>
                <select name="department" required> <option value="">Select Department</option> <option>Computer Science</option> <option>Software Engineering</option> <option>Information Technology</option> <option>Artificial Intelligence</option> </select>
            </div>

            <div class="form-group">
                <label>Semester</label>
                <input type="number" name="semester" min="1" max="8" required>
            </div>

            <div class="form-group">
                <label>CGPA</label>
                <input type="number" name="cgpa" step="0.01" min="0" max="4" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Insert Record</button>
                <a href="display.php" class="btn btn-secondary"><i class="fa-solid fa-book-open"></i> View All Records</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>
