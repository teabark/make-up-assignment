<?php
session_start();
include('../constants.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch users from the database
    $select_query = "SELECT user_id, user_name, email, phone_number, address, profile_image, usertype FROM users";
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
</head>

<body>
    <!-- TopBar -->
    <?php include "super_nav.php"; ?>

    <!-- Sidebar -->
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

            <!-- Display the user list -->
            <table border="1">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Profile Image</th>
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