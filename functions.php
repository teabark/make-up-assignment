<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('./constants.php');

// Include Dompdf library
require_once('./vendor/autoload.php');
use Dompdf\Dompdf;
use Dompdf\Options;

// Function to generate the report
function generateReport($format, $usertype, $users) {
    try {
        // Create Dompdf object
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        // Set paper size (optional)
        $dompdf->setPaper('A4', 'landscape');

        // Add content to Dompdf
        $html = '<html>
                    <head></head>
                    <body>
                        <h2>User List</h2>
                        <table border="1">
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
                            </thead>
                            <tbody>';

        // Add table rows
        $cnt = 1;
        foreach ($users as $row) {
            if ($row['usertype'] === $usertype) {
                $html .= '<tr>
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
        }

        // Close HTML
        $html .= '</tbody></table></body></html>';

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Render PDF (first pass to get the total pages)
        $dompdf->render();

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

        // Output the generated PDF
        $dompdf->stream();

    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}


try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch users from the database
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check the format preference (excel, pdf, csv)
    $format = isset($_GET['format']) ? $_GET['format'] : 'pdf';

    // Check the usertype
    $usertype = isset($_GET['usertype']) ? $_GET['usertype'] : 'administrator';

    // Call the generateReport function with the correct arguments
    generateReport($format, $usertype, $users);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>