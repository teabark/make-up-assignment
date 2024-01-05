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
<!-- user_management.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../dashboard.css">
</head>

<body>
<?php include "super_nav.php"; ?>
<?php include "super_sidebar.php"; ?>

    <!-- <div class="header"> -->
        <h2>User Management</h2>
    <!-- </div> -->

    <div class="sidebar">
        <ul>
            <li><a href="../signup.php">Add Users</a></li>
            <li><a href="view_users.php">View All Users</a></li>
            <li><a href="./update_users.php">Update User Details</a></li>
            <li><a href="./delete_users.php">Delete User</a></li>
            <li><a href="./export_user.php">Export Users</a></li>
            <li><a href="./superuser_dashboard.php">Home</a></li>
        </ul>
    </div>
    </body>

</html>
