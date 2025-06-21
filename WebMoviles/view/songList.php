<?php
include_once '../business/TrackBusiness.php';
$trackBusiness = new TrackBusiness();
$songsResult = $trackBusiness->getAllSongs();
$songs = $songsResult['response'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Songs - Rehearsal Cloud</title>
    <link rel="stylesheet" href="../css/homepage.css">
    <style>
        .songs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 32px;
        }
        .songs-table th, .songs-table td {
            border: 1px solid #e5e7eb;
            padding: 10px 8px;
            text-align: left;
        }
        .songs-table th {
            background: #f3f4f6;
        }
        .songs-table img {
            border-radius: 4px;
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
        .songs-list-title {
            margin-top: 32px;
            font-size: 2rem;
            font-weight: bold;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 16px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="logo-nav">
                <div class="logo">
                    <img src="../images/logoCloud.png" alt="Logo Rehearsal Cloud">
                    <span class="logo-text">Rehearsal Cloud</span>
                </div>
                <nav class="nav">
                    <a href="homepage.html">Home</a>
                    <a href="trackUploadForm.html">UploadTraks</a>
                    <a href="#">Your Tracks</a>
                    <a href="#">Rehearsal Connect</a>
                    <a href="#" onclick="openHelpWindow()">Open Help & Support</a>
                </nav>
            </div>
            <div class="header-actions">
                <a href="login.html" class="login-link">Log In</a>
                <a href="signup.html" class="btn btn-primary">Join Free</a>
            </div>
        </div>
    </header>

    <main class="songs-list-main">
        <div class="container">
            <h1 class="songs-list-title">All Registered Songs</h1>
            <?php if (!empty($songs)): ?>
            <table class="songs-table" id="songsTable">
                <thead>
                    <tr>
                        <th>Song Name</th>
                        <th>Artist</th>
                        <th>BPM</th>
                        <th>Tone</th>
                        <th>Album Art</th>
                        <th>ZIP File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($songs as $song): ?>
                    <tr>
                        <td><?= htmlspecialchars($song['songName'] ?? $song['SongName'] ?? '') ?></td>
                        <td><?= htmlspecialchars($song['artistName'] ?? $song['Artist'] ?? '') ?></td>
                        <td><?= htmlspecialchars($song['bpm'] ?? $song['BPM'] ?? '') ?></td>
                        <td><?= htmlspecialchars($song['tono'] ?? $song['Tone'] ?? '') ?></td>
                        <td>
                            <?php
                            $albumArt = null;
                            foreach (['albumArtPath', 'CoverImage', 'coverImage'] as $key) {
                                if (!empty($song[$key])) {
                                    $albumArt = $song[$key];
                                    // Si la ruta es absoluta, conviértela a relativa
                                    if (str_starts_with($albumArt, 'C:/xampp/htdocs/')) {
                                        $albumArt = str_replace('C:/xampp/htdocs/', '/', $albumArt);
                                    }
                                    break;
                                }
                            }
                            ?>
                            <?php if ($albumArt): ?>
                                <img src="<?= htmlspecialchars($albumArt) ?>" alt="Album Art">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $zipFile = null;
                            foreach (['zipFilePath', 'ZipFile', 'zipfile'] as $key) {
                                if (!empty($song[$key])) {
                                    $zipFile = $song[$key];
                                    // Si la ruta es absoluta, conviértela a relativa
                                    if (str_starts_with($zipFile, 'C:/xampp/htdocs/')) {
                                        $zipFile = str_replace('C:/xampp/htdocs/', '/', $zipFile);
                                    }
                                    break;
                                }
                            }
                            ?>
                            <?php if ($zipFile): ?>
                                <a href="<?= htmlspecialchars($zipFile) ?>" target="_blank" download>Download</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p id="noSongsMsg">No songs registered yet.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>