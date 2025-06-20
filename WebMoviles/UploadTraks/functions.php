<?php
// functions.php - Core functions

require_once 'config.php';

/**
 * Validate required form fields
 */
function validateFormData($data) {
    $errors = [];
    
    $required_fields = [
        'song_name' => 'Song name',
        'artist_name' => 'Artist name',
        'file_type' => 'File type',
        'option1' => 'First option',
        'option2' => 'Second option'
    ];
    
    foreach ($required_fields as $field => $label) {
        if (empty($data[$field])) {
            $errors[] = "$label is required";
        }
    }
    
    // Validate BPM
    $bpm = isset($data['bpm']) ? intval($data['bpm']) : 0;
    if ($bpm < 0 || $bpm > 300) {
        $errors[] = "BPM must be between 0 and 300";
    }
    
    return $errors;
}

/**
 * Handle ZIP file upload
 */
function handleZipUpload($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No ZIP file uploaded or upload error'];
    }
    
    // Validate file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, ALLOWED_ZIP_TYPES)) {
        return ['success' => false, 'message' => 'Invalid file type. Only ZIP files are allowed'];
    }
    
    // Validate file size
    if ($file['size'] > MAX_ZIP_SIZE) {
        return ['success' => false, 'message' => 'ZIP file too large. Maximum size is ' . (MAX_ZIP_SIZE / 1024 / 1024) . 'MB'];
    }
    
    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'zip_' . time() . '_' . uniqid() . '.' . $file_extension;
    $upload_path = UPLOAD_DIR . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return [
            'success' => true,
            'message' => 'ZIP file uploaded successfully',
            'file_path' => $upload_path,
            'original_name' => $file['name'],
            'file_size' => $file['size']
        ];
    }
    
    return ['success' => false, 'message' => 'Failed to save ZIP file'];
}

/**
 * Handle album art upload
 */
function handleAlbumArtUpload($file) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'message' => 'No album art uploaded', 'file_path' => null];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Album art upload error'];
    }
    
    // Validate file type
    if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
        return ['success' => false, 'message' => 'Invalid image type. Only JPEG and PNG are allowed'];
    }
    
    // Validate file size
    if ($file['size'] > MAX_IMAGE_SIZE) {
        return ['success' => false, 'message' => 'Image file too large. Maximum size is ' . (MAX_IMAGE_SIZE / 1024 / 1024) . 'MB'];
    }
    
    // Validate image dimensions
    $image_info = getimagesize($file['tmp_name']);
    if ($image_info === false) {
        return ['success' => false, 'message' => 'Invalid image file'];
    }
    
    if ($image_info[0] < 512 || $image_info[1] < 512) {
        return ['success' => false, 'message' => 'Image must be at least 512x512 pixels'];
    }
    
    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'album_' . time() . '_' . uniqid() . '.' . $file_extension;
    $upload_path = UPLOAD_DIR . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return [
            'success' => true,
            'message' => 'Album art uploaded successfully',
            'file_path' => $upload_path,
            'original_name' => $file['name']
        ];
    }
    
    return ['success' => false, 'message' => 'Failed to save album art'];
}

/**
 * Save form submission to JSON file
 */
function saveSubmission($data) {
    $submissions = [];
    
    if (file_exists(SUBMISSIONS_FILE)) {
        $json_content = file_get_contents(SUBMISSIONS_FILE);
        $submissions = json_decode($json_content, true) ?? [];
    }
    
    $submission = [
        'id' => uniqid(),
        'song_name' => htmlspecialchars($data['song_name']),
        'artist_name' => htmlspecialchars($data['artist_name']),
        'file_type' => htmlspecialchars($data['file_type']),
        'bpm' => intval($data['bpm']),
        'option1' => htmlspecialchars($data['option1']),
        'option2' => htmlspecialchars($data['option2']),
        'zip_file' => $data['zip_file'] ?? null,
        'album_art' => $data['album_art'] ?? null,
        'submitted_at' => date('Y-m-d H:i:s')
    ];
    
    $submissions[] = $submission;
    
    return file_put_contents(SUBMISSIONS_FILE, json_encode($submissions, JSON_PRETTY_PRINT)) !== false;
}

/**
 * Get all submissions
 */
function getSubmissions() {
    if (!file_exists(SUBMISSIONS_FILE)) {
        return [];
    }
    
    $json_content = file_get_contents(SUBMISSIONS_FILE);
    return json_decode($json_content, true) ?? [];
}

/**
 * Generate JSON response
 */
function jsonResponse($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>