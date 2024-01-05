<?php
session_start();
include('../constants.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in and has the correct user type
$allowedUserTypes = ['superuser', 'administrator', 'author'];

if (!isset($_SESSION['user']) || !in_array($_SESSION['usertype'], $allowedUserTypes)) {
    var_dump($_SESSION);
    header('Location: index.php');
    exit;
}


try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch users from the database
    $select_query = "SELECT user_id, user_name, email, phone_number, address, profile_image FROM users";
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
    <title>User List</title>
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
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <?php include "super_nav.php"; ?>
    <?php include "super_sidebar.php"; ?>

    <div id="content">
        <h2>User List</h2>

        <?php if (!empty($users)) : ?>
            <!-- Display the form for choosing format and usertype -->
            <form method="get" action="../functions.php">
                <label for="format">Select Format:</label>
                <select id="format" name="format">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="csv">CSV</option>
                </select>

                <label for="usertype">Select User Type:</label>
                <select id="usertype" name="usertype">
                    <option value="administrator">Administrator</option>
                    <option value="author">Author</option>
                    <option value="superuser">Super User</option>
                    <!-- Add other user types as needed -->
                </select>

                <button type="submit">Download Report</button>
            </form>

            <table border="1">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>profile_image</th>
                    <th>User Type</th>
                </tr>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo $user['user_name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['address']; ?></td>
                        <td><?php echo $user['profile_image']; ?></td>
                        <td><?php echo $user['usertype']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>

</body>

</html>