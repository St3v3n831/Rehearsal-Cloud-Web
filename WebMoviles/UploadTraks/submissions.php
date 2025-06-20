<?php
// submissions.php - View all submissions

require_once 'functions.php';

$submissions = getSubmissions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Submissions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>All Track Submissions</h1>
            
            <?php if (empty($submissions)): ?>
                <p>No submissions found.</p>
            <?php else: ?>
                <div class="submissions-list">
                    <?php foreach (array_reverse($submissions) as $submission): ?>
                        <div class="submission-item">
                            <div class="submission-header">
                                <h3><?php echo htmlspecialchars($submission['song_name']); ?></h3>
                                <span class="submission-date"><?php echo htmlspecialchars($submission['submitted_at']); ?></span>
                            </div>
                            <div class="submission-details">
                                <p><strong>Artist:</strong> <?php echo htmlspecialchars($submission['artist_name']); ?></p>
                                <p><strong>File Type:</strong> <?php echo htmlspecialchars($submission['file_type']); ?></p>
                                <p><strong>BPM:</strong> <?php echo $submission['bpm']; ?></p>
                                <p><strong>ID:</strong> <?php echo htmlspecialchars($submission['id']); ?></p>
                                <?php if ($submission['zip_file']): ?>
                                    <p><strong>ZIP File:</strong> Uploaded</p>
                                <?php endif; ?>
                                <?php if ($submission['album_art']): ?>
                                    <p><strong>Album Art:</strong> Uploaded</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="actions">
                <a href="index.html" class="btn btn-primary">Submit New Track</a>
            </div>
        </div>
    </div>
</body>
</html>