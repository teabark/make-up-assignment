<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in and is a Super_User
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'author') {
    echo 'user not logged in as author';
    var_dump($_SESSION);
    header('Location: index.php'); // Redirect to login page if not logged in or not a Super_User
    exit;
}

?>

<!-- author_dashboard.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Dashboard</title>
    <link rel="stylesheet" href="../dashboard.css">
</head>

<body>
    <?php include('author_nav.php');?>
    <?php include('author_sidebar.php');?>
    <!-- <footer><p>&copy; 2024 Denzel Jones. All rights reserved.</p></footer> -->
</body>

</html>

