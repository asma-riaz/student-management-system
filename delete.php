<?php

include 'db_connect.php';

$success = "";
$error   = "";
$student = null;

// ── STEP 1: Load student by ID (GET — show confirmation) ──────
if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT id, name, age, email FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result  = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    if (!$student) {
        $error = "Student with ID $id not found.";
    }
}

// ── STEP 2: Handle confirmed deletion (POST) ──────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // First fetch the name for the confirmation message
    $stmt = $conn->prepare("SELECT name FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    if ($row) {
        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $success = "✅ Student <strong>" . htmlspecialchars($row['name']) . "</strong> has been deleted.";
        } else {
            $error = " Could not delete: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = " Student not found.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student | College DB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fa-regular fa-rectangle-xmark"></i> Delete Student Record</h1>
        <p>Permanently remove a student from the database.</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= $success ?> <a href="display.php">← Back to records</a>
        </div>

    <?php elseif ($error): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?> <a href="display.php">← Go back</a>
        </div>

    <?php elseif ($student): ?>
        <!-- Confirmation card -->
        <div class="card delete-confirm">
            <div class="confirm-icon"><i class="fa-regular fa-square-check"></i></div>
            <h2>Are you sure?</h2>
            <p>You are about to permanently delete the following student:</p>

            <table class="confirm-table">
                <tr><th>ID</th>    <td><?= $student['id'] ?></td></tr>
                <tr><th>Name</th>  <td><?= htmlspecialchars($student['name']) ?></td></tr>
                <tr><th>Age</th>   <td><?= $student['age'] ?></td></tr>
                <tr><th>Email</th> <td><?= htmlspecialchars($student['email']) ?></td></tr>
            </table>

            <p class="warning-text">This action <strong>cannot be undone.</strong></p>

            <div class="form-actions">
                <!-- Confirmed delete via POST -->
                <form method="POST" action="delete.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $student['id'] ?>">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
                <a href="display.php" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i>Cancel</a>
            </div>
        </div>

    <?php else: ?>
        <!-- No ID passed — show input form -->
        <div class="card">
            <p>Enter the student ID to delete:</p>
            <form method="GET" action="delete.php">
                <div class="form-group">
                    <label for="id">Student ID</label>
                    <input type="number" id="id" name="id" placeholder="e.g. 3" min="1" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-magnifying-glass"></i> Find &amp; Delete</button>
                    <a href="display.php" class="btn btn-secondary"><i class="fa-solid fa-book-open"></i> View All</a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
