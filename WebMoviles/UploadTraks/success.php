<?php
// success.php - Success page after form submission

require_once 'functions.php';

$submission_id = $_GET['id'] ?? '';
$submissions = getSubmissions();
$submission = null;

// Find the submission by ID
foreach ($submissions as $sub) {
    if ($sub['id'] === $submission_id) {
        $submission = $sub;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Successful</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Track Information Submitted Successfully!</h1>
            
            <div class="success-message">
                <p>Thank you for submitting your track information. We have received your submission and will process it shortly.</p>
            </div>
            
            <?php if ($submission): ?>
                <div class="submission-details">
                    <h3>Submission Details:</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <strong>Song Name:</strong>
                            <span><?php echo htmlspecialchars($submission['song_name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>Artist Name:</strong>
                            <span><?php echo htmlspecialchars($submission['artist_name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>File Type:</strong>
                            <span><?php echo htmlspecialchars($submission['file_type']); ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>BPM:</strong>
                            <span><?php echo $submission['bpm']; ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>Submission ID:</strong>
                            <span><?php echo htmlspecialchars($submission['id']); ?></span>
                        </div>
                        <div class="detail-item">
                            <strong>Submitted At:</strong>
                            <span><?php echo htmlspecialchars($submission['submitted_at']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="actions">
                <a href="index.html" class="btn btn-primary">Submit Another Track</a>
                <a href="submissions.php" class="btn btn-secondary">View All Submissions</a>
            </div>
        </div>
    </div>
</body>
</html>