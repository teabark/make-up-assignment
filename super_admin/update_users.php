<?php
session_start();
include('../constants.php');

// Check if the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'superuser') {
    var_dump($_SESSION);
    header('Location: index.php');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $user_id = $_POST['user_id'];
    $newUsername = $_POST['new_username'];
    $newEmail = $_POST['new_email'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash the new password

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update user details in the database
        $update_query = "UPDATE users SET user_name = :newUsername, email = :newEmail, password = :newPassword WHERE user_id = :user_id";
        $stmt = $pdo->prepare($update_query);
        $stmt->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
        $stmt->bindParam(':newEmail', $newEmail, PDO::PARAM_STR);
        $stmt->bindParam(':newPassword', $newPassword, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        echo '<p style="color: green;">User details updated successfully!</p>';
    } catch (PDOException $e) {
        echo '<p style="color: red;">Error updating user details: ' . $e->getMessage() . '</p>';
    }
}

// Fetch users from the database
try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch users for the selection form
    $select_query = "SELECT user_id, user_name FROM users";
    $stmt = $pdo->query($select_query);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Details</title>
    <link rel="stylesheet" href="../dashboard.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        select,
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
            padding: 10px 15px;
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
    <?php include "super_nav.php"; ?>
    <?php include "super_sidebar.php"; ?>
    <h2>Update User Details</h2>

    <!-- User Selection Form -->
    <form method="post" action="">
        <label for="user_id">Select User:</label>
        <select name="user_id" id="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <!-- New User Details Form -->
        <label for="new_username">New Username:</label>
        <input type="text" id="new_username" name="new_username" required><br>

        <label for="new_email">New Email:</label>
        <input type="email" id="new_email" name="new_email" required><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>

        <button type="submit">Update User Details</button>
    </form>
    
</body>
</html>
