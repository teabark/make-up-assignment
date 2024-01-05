<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('../constants.php');

// Check the format preference (excel, pdf, csv)
$format = isset($_GET['format']) ? $_GET['format'] : 'csv';

// Include TCPDF library (for PDF)
if ($format === 'pdf') {
    require_once('tcpdf/tcpdf.php');
}

// Set content-type and filename based on the format
if ($format === 'excel') {
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=Users_list_report.xls");
} elseif ($format === 'pdf') {
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=Users_list_report.pdf");
} elseif ($format === 'csv') {
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=Users_list_report.csv");
}

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch users from the database
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add table header
    echo '<table border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Id</th>
                    <th>Full Name</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>User type</th>
                    <!-- Add other table headers as needed -->
                </tr>
            </thead>';

    // Add table rows based on the format
    $cnt = 1;
    foreach ($users as $row) {
        echo '<tr>
                <td>' . $cnt . '</td>
                <td>' . $row['user_id'] . '</td>
                <td>' . $row['full_name'] . '</td>
                <td>' . $row['user_name'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['phone_number'] . '</td>
                <td>' . $row['usertype'] . '</td>
              </tr>';

        $cnt++;
    }

    // Close the table
    echo '</table>';

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>