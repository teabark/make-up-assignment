<?php
session_start();
include('../constants.php');

// Check if the user is logged in and is a Super_User
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'author') {
    echo 'user not logged in as author';
    var_dump($_SESSION);
    header('Location: index.php'); // Redirect to login page if not logged in or not a Super_User
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $authorId = $_POST['authorId']; // Replace with the actual author ID from the logged-in user
    $title = $_POST['title'];
    $fullText = $_POST['full_text'];
    $createdDate = date('Y-m-d H:i:s'); // Current timestamp
    $lastUpdate = date('Y-m-d H:i:s'); // Initial value is the same as created date
    $display = $_POST['display'];
    $order = $_POST['order'];

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert article details into the database
        $insert_query = "INSERT INTO articles (authorId, article_title, article_full_text, article_created_date, article_last_update, article_display, article_order) 
                         VALUES (:authorId, :title, :fullText, :createdDate, :lastUpdate, :display, :order)";
        $stmt = $pdo->prepare($insert_query);
        $stmt->bindParam(':authorId', $authorId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':fullText', $fullText, PDO::PARAM_STR);
        $stmt->bindParam(':createdDate', $createdDate, PDO::PARAM_STR);
        $stmt->bindParam(':lastUpdate', $lastUpdate, PDO::PARAM_STR);
        $stmt->bindParam(':display', $display, PDO::PARAM_STR);
        $stmt->bindParam(':order', $order, PDO::PARAM_INT);
        $stmt->execute();

        echo '<p style="color: green;">Article added successfully!</p>';
    } catch (PDOException $e) {
        echo '<p style="color: red;">Error adding article: ' . $e->getMessage() . '</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
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
            max-width: 600px;
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

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
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
    <?php include('author_nav.php'); ?>
    <?php include('author_sidebar.php'); ?>
    <h2>Add Article</h2>

    <!-- Article Form -->
    <form method="post" action="">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="full_text">Full Text:</label><br>
        <textarea id="full_text" name="full_text" rows="20" cols="100" required></textarea><br>

        <label for="display">Display (yes or no):</label>
        <input type="text" id="display" name="display" required><br>

        <label for="order">Order:</label>
        <input type="text" id="order" name="order" required><br>

        <input type="hidden" name="authorId" value="1"> <!-- Replace with the actual authorId from the logged-in user -->

        <button type="submit">Add Article</button>
    </form>
</body>

</html>