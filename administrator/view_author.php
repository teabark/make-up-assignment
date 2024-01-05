<?php
include('../constants.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch users with usertype 'author' from the database
    $select_query = "SELECT user_id, user_name, email, phone_number, address, profile_image FROM users WHERE usertype = 'author'";
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
</head>

<body>
    <h2>Author List</h2>

    <?php if (!empty($users)) : ?>
        <table border="1">
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Address</th>
                <th>Profile Image</th>
            </tr>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo $user['user_name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['address']; ?></td>
                    <td><?php echo $user['profile_image']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No users found with the usertype 'author'.</p>
    <?php endif; ?>

</body>

</html>
