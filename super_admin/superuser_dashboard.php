<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in and is a Super_User
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'superuser') {
    echo 'user not logged in as superuser';
    var_dump($_SESSION);
    header('Location: index.php'); // Redirect to login page if not logged in or not a Super_User
    exit;
}

?>

<!-- superuser_dashboard.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superuser Dashboard</title>
    <link rel="stylesheet" href="../dashboard.css">
</head>

<body>

    <?php include "super_nav.php"; ?>
    <?php include "super_sidebar.php"; ?>

    <div id="content">
        fidukdutdctchxysy5
    </div>
    <!-- <footer><p>&copy; 2024 Denzel Jones. All rights reserved.</p></footer> -->
</body>

</html>