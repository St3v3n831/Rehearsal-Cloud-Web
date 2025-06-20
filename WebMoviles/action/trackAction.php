
<?php
include_once "../business/TrackBusiness.php";
include_once "../UploadTraks/functions.php";

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$response = ['success' => false, 'message' => '', 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Procesa archivos y datos igual que antes
$form_data = sanitizeInput($_POST);
$validation_errors = validateFormData($form_data);

if (!empty($validation_errors)) {
    $response['errors'] = $validation_errors;
    $response['message'] = 'Please fix the validation errors';
    echo json_encode($response);
    exit;
}

// Manejo de archivos
$zip_result = handleZipUpload($_FILES['zip_file'] ?? null);
$album_result = handleAlbumArtUpload($_FILES['album_art'] ?? null);

if (!$zip_result['success']) {
    $response['errors'][] = $zip_result['message'];
}
if (!$album_result['success'] && $album_result['message'] !== 'No album art uploaded') {
    $response['errors'][] = $album_result['message'];
}

if (!empty($response['errors'])) {
    echo json_encode($response);
    exit;
}

// Prepara datos para la API
$trackData = [
    'songName'      => $form_data['song_name'],
    'artistName'    => $form_data['artist_name'],
    'bpm'           => $form_data['bpm'],
    'tono'          => $form_data['option2'],
    'albumArtPath'  => $album_result['file_path'] ?? null,
    'zipFilePath'   => $zip_result['file_path'] ?? null
];

$trackBusiness = new TrackBusiness();
$apiResult = $trackBusiness->submitTrack($trackData);

if ($apiResult['success']) {
    $response['success'] = true;
    $response['message'] = $apiResult['message'];
    $response['data'] = $apiResult['data'];
} else {
    $response['message'] = $apiResult['message'];
    $response['errors'][] = $apiResult['error'];
}

echo json_encode($response);
exit;
?>