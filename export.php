<?php
require_once('database.php');

// Retrieve data from the database
$db = new DBConnection();
$conn = $db->conn;
$query = $conn->query("SELECT * FROM `members` order by id asc");
$data = array();
while ($row = $query->fetch_assoc()) {
    $data[] = array(
        $row['id'],
        $row['name'],
        $row['email'],
        $row['contact'],
        $row['address'],
    );
}
// Set appropriate headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="members.csv"');

// Clear output buffer before outputting CSV data
ob_clean();

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the CSV column headers
fputcsv($output, array('ID', 'Name', 'Email', 'Contact', 'Address'));

// Output the data rows
foreach ($data as $row) {
    fputcsv($output, $row);
}

// Close the file pointer
fclose($output);
exit;
?>
