<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../constants.php');

// Check if the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'author') {
    var_dump($_SESSION);
    header('Location: index.php');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $article_id = $_POST['articleId'];

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete article from the database
        $delete_query = "DELETE FROM articles WHERE articleId = :articleId";
        $stmt = $pdo->prepare($delete_query);
        $stmt->bindParam(':articleId', $article_id, PDO::PARAM_INT);
        $stmt->execute();

        echo '<p style="color: green;">Article deleted successfully!</p>';
    } catch (PDOException $e) {
        echo '<p style="color: red;">Error deleting article: ' . $e->getMessage() . '</p>';
    }
}

// Fetch articles from the database
try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all articles from the articles table
    $select_query = "SELECT * FROM articles";
    $stmt = $pdo->query($select_query);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Article</title>
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

        button {
            background-color: #ff3333;
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
    <?php include "./author_nav.php"; ?>
    <?php include "./author_sidebar.php"; ?>
    <div id="content">
        <h2>Delete Article</h2>

        <?php if (!empty($articles)) : ?>
            <!-- Article Deletion Form -->
            <form method="post" action="">
                <label for="articleId">Select Article to Delete:</label>
                <select name="articleId" required>
                    <?php foreach ($articles as $article) : ?>
                        <option value="<?php echo $article['articleId']; ?>"><?php echo $article['article_title']; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <button type="submit">Delete Article</button>
            </form>
        <?php else : ?>
            <p>No articles found.</p>
        <?php endif; ?>
    </div>
</body>

</html>
