<?php
require_once('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        // Check if the uploaded file is a CSV file
        $file_info = pathinfo($_FILES['csv_file']['name']);
        if (strtolower($file_info['extension']) !== 'csv') {
            echo "Error: Please upload a CSV file.";
            exit;
        }

        // Check the file size (in bytes)
        $file_size = $_FILES['csv_file']['size'];
        $max_size = 7 * 1024; // 7KB in bytes
        if ($file_size > $max_size) {
            echo '<div class="alert alert-danger" role="alert">Error: File size exceeds the limit of 7KB. Cannot import.</div>';
            exit;
        }

        // Open the uploaded CSV file
        $file_handle = fopen($_FILES['csv_file']['tmp_name'], 'r');

        // header row (if it exists)
        fgetcsv($file_handle, 1000, ',');

        // Prepare and execute the SQL INSERT statement for each row in the CSV file
        $db = new DBConnection();
        $conn = $db->conn;

        while (($data = fgetcsv($file_handle, 1000, ',')) !== false) {
            $id = $data[0];
            $name = $data[1];
            $email = $data[2];
            $contact = $data[3];
            $address = $data[4];

            $query = "INSERT INTO members (name, email, contact, address) 
                      VALUES ('$name', '$email', '$contact', '$address')";
            mysqli_query($conn, $query);
        }

        fclose($file_handle);
        echo '<div class="alert alert-success" role="alert">CSV data imported successfully!</div>';
        exit; // Exit here to prevent additional content from being sent (like the HTML below)
    } else {
        echo '<div class="alert alert-danger" role="alert">Error uploading the CSV file.</div>';
        exit; // Exit here to prevent additional content from being sent (like the HTML below)
    }
}
?>
