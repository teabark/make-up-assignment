<?php
session_start();
include('../constants.php');

// Check if the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'administrator') {
    var_dump($_SESSION);
    header('Location: index.php');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $user_id = $_POST['user_id'];

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete user from the database only for users with usertype 'author'
        $delete_query = "DELETE FROM users WHERE user_id = :user_id AND usertype = 'author'";
        $stmt = $pdo->prepare($delete_query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo '<p style="color: green;">User deleted successfully!</p>';
        } else {
            echo '<p style="color: red;">Error deleting user: User not found or does not have the required usertype.</p>';
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">Error deleting user: ' . $e->getMessage() . '</p>';
    }
}

// Fetch users with usertype 'author' from the database
try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch users for the selection form with usertype 'author'
    $select_query = "SELECT user_id, user_name FROM users WHERE usertype = 'author'";
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
    <title>Delete User</title>
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
            margin-top: 20px;
        }

        form {
            max-width: 300px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #ff5555;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #ff0000;
        }
    </style>
</head>

<body>
<?php include "adm_nav.php"; ?>
<?php include "adm_sidebar.php"; ?>
    <h2>Delete User</h2>

    <!-- User Selection Form -->
    <form method="post" action="">
        <label for="user_id">Select User:</label>
        <select name="user_id" id="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <button type="submit">Delete User</button>
    </form>
</body>
</html>
