<?php
// config.php - Configuration settings

define('UPLOAD_DIR', 'uploads/');
define('MAX_ZIP_SIZE', 100 * 1024 * 1024); // 100MB
define('MAX_IMAGE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_ZIP_TYPES', ['application/zip', 'application/x-zip-compressed']);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png']);
define('SUBMISSIONS_FILE', 'data/submissions.json');

// Create necessary directories
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

if (!is_dir('data')) {
    mkdir('data', 0755, true);
}
?>