<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <!-- navbar.php — Include this at the top of every page -->
<nav class="navbar">
    <div class="nav-brand">College DB</div>
    <ul class="nav-links">
        <li><a href="display.php"       class="<?= basename($_SERVER['PHP_SELF']) === 'display.php'       ? 'active' : '' ?>"><i class="fa-solid fa-book-open"></i> View Records</a></li>
        <li><a href="departments.php"  class="<?= basename($_SERVER['PHP_SELF']) === 'departments.php'  ? 'active' : '' ?>"><i class="fa-solid fa-building-columns"></i> Departments</a></li>
        <li><a href="insert.php"   class="<?= basename($_SERVER['PHP_SELF']) === 'insert.php'   ? 'active' : '' ?>"><i class="fa-solid fa-plus"></i> Insert</a></li>
        <li><a href="update.php"   class="<?= basename($_SERVER['PHP_SELF']) === 'update.php'   ? 'active' : '' ?>"><i class="fa-solid fa-pen-to-square"></i> Update</a></li>
        <li><a href="delete.php"   class="<?= basename($_SERVER['PHP_SELF']) === 'delete.php'   ? 'active' : '' ?>"><i class="fa-regular fa-rectangle-xmark"></i> Delete</a></li>
    </ul>
</nav>
</body>
</html>