<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../constants.php');

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch articles from the database
    $select_query = "SELECT * FROM articles";
    var_dump($select_query);
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
    <title>List of Articles</title>
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

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
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

        p {
            text-align: center;
            color: #555;
        }
    </style>
</head>

<body>
    <h2>List of Articles</h2>

    <?php if (!empty($articles)) : ?>
        <table border="1">
            <tr>
                <th>Article ID</th>
                <th>Title</th>
                <th>Author ID</th>
                <th>Created Date</th>
                <th>Last Update</th>
                <th>Display</th>
                <th>Order</th>
            </tr>
            <?php foreach ($articles as $article) : ?>
                <tr>
                    <td><?php echo $article['articleId']; ?></td>
                    <td><?php echo $article['article_title']; ?></td>
                    <td><?php echo $article['authorId']; ?></td>
                    <td><?php echo $article['article_created_date']; ?></td>
                    <td><?php echo $article['article_last_update']; ?></td>
                    <td><?php echo $article['article_display']; ?></td>
                    <td><?php echo $article['article_order']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No articles found.</p>
    <?php endif; ?>

</body>

</html>
