<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('./constants.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $user_name = $_POST['user_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $user_type = $_POST['user_type'];
    $access_time = $_POST['access_time'];
    $formatted_access_time = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' ' . $access_time));
    $profile_image = $_POST['profile_image'];
    $address = $_POST['address'];

    $mysqli = new mysqli($hostname, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Insert user into the database
    $insert_query = "INSERT INTO users (full_name, email, phone_number, user_name, password, usertype, access_time, profile_Image, address) 
                         VALUES ('$full_name', '$email', '$phone_number', '$user_name', '$password', '$user_type', '$formatted_access_time', '$profile_image', '$address')";

    if ($mysqli->query($insert_query) === TRUE) {
        echo '<p style="color: green;">User successfully registered.</p>';
    } else {
        echo '<p style="color: red;">Error: ' . $mysqli->error . '</p>';
    }

    $mysqli->close();
}
// Assuming $defaultAccessTime is a timestamp
$defaultAccessTime = time(); // Replace this with your actual timestamp

// Convert timestamp to HH:mm format
$defaultTimeValue = date("H:i", $defaultAccessTime);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Users</title>
    <link rel="stylesheet" href="./dashboard.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.header h1 {
    margin: 0;
}

.header span {
    margin-right: 20px;
}

.header a {
    color: #fff;
    text-decoration: none;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar li {
    margin-bottom: 10px;
}

.sidebar a {
    color: #fff;
    text-decoration: none;
}

#content {
    margin-left: 2opx;
    padding: 10px;
}

h2 {
    color: #333;
}

form {
    max-width: 400px;
    margin: 20px auto;
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

input,
textarea,
select {
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
    <div class="header">
        <h1>Superuser Dashboard</h1>
        <div>
            <!-- Display logged user name -->
            <span>Welcome, <?php echo ucfirst($_SESSION['user']); ?>!</span>
            <!-- Logout button -->
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    <div class="sidebar">
        <ul>
            <li><a href="./super_admin/update_profile.php">Update Profile</a></li>
            <li><a href="./super_admin/manage_users.php">Manage Other Users</a></li>
            <li><a href="./super_admin/view_users.php">View User</a></li>
        </ul>
    </div>
    
    <div id="content">
    <h2>Add Users</h2>
   
    <form method="post" action="">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number"><br>

        <label for="user_name">User Name:</label>
        <input type="text" id="user_name" name="user_name" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="user_type">User Type:</label>
        <input type="text" id="user_type" name="user_type"><br>

        <label for="access_time">Access Time:</label>
        <input type="time" id="access_time" name="access_time" value="<?php echo $defaultTimeValue; ?>"><br>

        <label for="profile_image">Profile Image:</label>
        <input type="text" id="profile_image" name="profile_image"><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address"></textarea><br>

        <button type="submit">Sign Up</button>
    </form>
    </div>
</body>

</html>