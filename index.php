<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);


include('./constants.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve user from the database based on username
        $select_query = "SELECT user_id, user_name, password, usertype FROM users WHERE user_name = :username";
        $stmt = $pdo->prepare($select_query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // For debugging: echo the executed SQL query and bound parameters
        echo "SQL Query: " . $select_query . "<br>";
        echo "Bound Parameters: ";
        var_dump([':username' => $username]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // For debugging: echo the fetched row
            echo "Fetched Row: ";
            var_dump($row);

            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user'] = $row['user_name'];

                // Determine user type and set session variable
                $userType = $row['usertype'];
                $_SESSION['usertype'] = $userType;

                // Redirect based on user type
                if ($userType === 'superuser') {
                    header('Location: super_admin/superuser_dashboard.php');
                    exit;
                } elseif ($userType === 'administrator') {
                    header('Location: administrator/admin_dashboard.php');
                    exit;
                } elseif ($userType === 'author') {
                    header('Location: author/author_dashboard.php');
                    exit;
                }
            } else {
                echo '<p style="color: red;">Invalid password. Please try again.</p>';
            }
        } else {
            echo '<p style="color: red;">User not found. Please check your username.</p>';
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            background-color: goldenrod;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: green;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h2>Login</h2>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>

        <?php
            // Display error messages
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($e)) {
                echo '<p style="color: red;">' . $e->getMessage() . '</p>';
            }
        ?>
    </form>
</body>
</html>

