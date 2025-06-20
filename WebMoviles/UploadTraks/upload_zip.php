<?php
// upload_zip.php - Handle ZIP file uploads via AJAX

require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

if (!isset($_FILES['zip_file'])) {
    jsonResponse(['success' => false, 'message' => 'No file uploaded']);
}

$result = handleZipUpload($_FILES['zip_file']);
jsonResponse($result);
?>