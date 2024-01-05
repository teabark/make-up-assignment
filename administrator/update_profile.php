<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../constants.php');

// Check if the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'administrator') {
    var_dump($_SESSION);
    header('Location: index.php');
    exit;
}

// Establish a database connection
try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission to update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];

    // Validate and update the password
    if (!empty($newPassword)) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        // Adjust this query according to your database structure
        $updateQuery = "UPDATE users SET password = :hashedPassword WHERE user_id = :user_id";

        try {
            $stmt = $pdo->prepare($updateQuery);
            $stmt->bindParam(':hashedPassword', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            echo '<p style="color: green;">Password updated successfully!</p>';
        } catch (PDOException $e) {
            echo '<p style="color: red;">Error updating password: ' . $e->getMessage() . '</p>';
        }
    } else {
        echo '<p style="color: red;">New password cannot be empty.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="../dashboard.css">
    <style>
form {
    max-width: 300px;
    margin: 20px auto;
    margin-left: 20%; /* Add this line to move the form to the right */
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

input {
    width: 100%;
    padding: 8px;
    margin-bottom: 16px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background-color: #4caf50;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}
    </style>
</head>
<body>
<?php include "adm_nav.php"; ?>
<?php include "adm_sidebar.php"; ?>

    <div id="content">
    <h2>Update Profile</h2>

    <form method="post" action="">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>

        <button type="submit">Update Password</button>
    </form>
    </div>
</body>
</html>
