<?php
session_start();

// Check if the user is logged in and is an Administrator
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'administrator') {
    header('Location: index.php'); // Redirect to login page if not logged in or not an Administrator
    exit;
}

?>

<!-- administrator_dashboard.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard</title>
    <link rel="stylesheet" href="../dashboard.css">
</head>

<body>

<?php include "./adm_nav.php"; ?>
<?php include "./adm_sidebar.php"; ?>
   
  </body>
</html>
