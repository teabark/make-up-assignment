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


try {
    // Establish a PDO database connection
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $articleId = $_POST["article_id"];
        $newAuthorId = $_POST["new_author_id"];
        $newTitle = $_POST["new_title"];
        $newFullText = $_POST["new_full_text"];

        // Query to retrieve existing article details
        $selectExistingQuery = "SELECT authorId, article_title, article_full_text FROM articles WHERE articleId = :articleId";
        $stmtExisting = $pdo->prepare($selectExistingQuery);
        $stmtExisting->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        $stmtExisting->execute();
        $existingValues = $stmtExisting->fetch(PDO::FETCH_ASSOC);

        // Query to update the article
        $updateQuery = "UPDATE articles SET authorId = :newAuthorId, article_title = :newTitle, article_full_text = :newFullText WHERE articleId = :articleId";
        $stmtUpdate = $pdo->prepare($updateQuery);
        $stmtUpdate->bindParam(':newAuthorId', $newAuthorId, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':newTitle', $newTitle, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':newFullText', $newFullText, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        $stmtUpdate->execute();

        echo "<h2>Article updated successfully!</h2>";

        // Display existing article details
        echo "<h3>Existing Article Details:</h3>";
        echo "<p>Author ID: {$existingValues['authorId']}</p>";
        echo "<p>Article Title: {$existingValues['article_title']}</p>";
        echo "<p>Article Full Text: {$existingValues['article_full_text']}</p>";

        // Display updated article details
        echo "<h3>Updated Article Details:</h3>";
        echo "<p>Author ID: $newAuthorId</p>";
        echo "<p>Article Title: $newTitle</p>";
        echo "<p>Article Full Text: $newFullText</p>";

        // Close the statements
        $stmtExisting->closeCursor();
        $stmtUpdate->closeCursor();
    }

    // Fetch articles for the dropdown
    $selectQuery = "SELECT articleId, article_title FROM articles";
    $stmt = $pdo->query($selectQuery);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Close the connection
    $pdo = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Article</title>
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
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            height: 150px; /* Increased height for the textarea */
            resize: vertical; /* Allow vertical resizing */
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <?php include('author_nav.php');?>
    <?php include('author_sidebar.php');?>
    <h2>Update Article Form</h2>

    <form action="" method="post">
        <label for="article_id">Select Article to Update:</label>
        <select name="article_id" required>
            <?php
            // Display each article as an option in the dropdown
            foreach ($articles as $article) {
                echo "<option value='{$article['articleId']}'>{$article['article_title']}</option>";
            }
            ?>
        </select>
        <br>
        <label for="new_author_id">New Author ID:</label>
        <input type="text" name="new_author_id" required>
        <br>
        <label for="new_title">New Article Title:</label>
        <input type="text" name="new_title" required>
        <br>
        <label for="new_full_text">New Article Full Text:</label>
        <textarea name="new_full_text" required></textarea>
        <br>
        <input type="submit" value="Update Article">
    </form>
</body>

</html>

