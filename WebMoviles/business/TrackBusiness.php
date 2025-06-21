<?php
include_once __DIR__ . '/../data/ApiHandler.php';

class TrackBusiness
{
    private $apiHandler;

    public function __construct()
    {
        $this->apiHandler = new ApiHandler();
    }

    public function submitTrack(array $trackData)
    {
        $result = $this->apiHandler->createSong($trackData);

        if ($result['status_code'] === 200 || $result['status_code'] === 201) {
            return [
                'success' => true,
                'message' => $result['response']['message'] ?? 'Track enviado correctamente',
                'data' => $result['response'] ?? [],
                'statusCode' => $result['status_code']
            ];
        } else {
            return [
                'success' => false,
                'message' => $result['response']['message'] ?? 'Error al enviar track',
                'error' => $result['response']['error'] ?? 'API_ERROR',
                'statusCode' => $result['status_code']
            ];
        }
    }

    public function getAllSongs()
    {
        $result = $this->apiHandler->getAllSongs();

        if ($result['status_code'] === 200) {
            return [
                'success' => true,
                'response' => $result['response'],
                'statusCode' => $result['status_code']
            ];
        } else {
            return [
                'success' => false,
                'response' => [],
                'error' => $result['response']['error'] ?? 'API_ERROR',
                'statusCode' => $result['status_code']
            ];
        }
    }
}
?>