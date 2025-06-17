<?php
include_once "../business/AuthBusiness.php";
include_once "../domain/Auth.php";

// Configuración inicial de cabeceras
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Manejo de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$authBusiness = new AuthBusiness();

try {
    // Verificar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    $option = $_POST['option'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $email = $_POST['email'] ?? null;

    // Validar opción
    if (!$option) {
        throw new Exception('Parámetro "option" es requerido', 400);
    }

    // Procesar según la opción
    switch ($option) {
        case '1': // Registrar usuario
            if (empty($username) || empty($email) || empty($password)) {
                throw new Exception('Todos los campos son obligatorios', 400);
            }
            
            // Validación adicional en el frontend como en Kotlin
            if (strlen($password) < 8) {
                throw new Exception('La contraseña debe tener al menos 8 caracteres', 400);
            }
            
            $user = new Auth(null, $username, $email, $password);
            $response = $authBusiness->registerUser($user);
            
            // Estructura consistente con Kotlin
            $jsonResponse = [
                'success' => $response['success'],
                'message' => $response['message'],
                'error' => $response['error'] ?? null,
                'data' => $response['data'] ?? null
            ];
            
            http_response_code($response['statusCode']);
            echo json_encode($jsonResponse, JSON_UNESCAPED_UNICODE);
            exit;

        case '2': // Iniciar sesión
            if (empty($username) || empty($password)) {
                throw new Exception('Usuario y contraseña son obligatorios', 400);
            }
            $user = new Auth(null, $username, '', $password);
            $response = $authBusiness->loginUser($user);
            
            // Estructura de respuesta consistente con .NET
            $jsonResponse = [
                'success' => $response['success'],
                'message' => $response['message'],
                'error' => $response['error'] ?? null
            ];

            // Solo agregar token y user si el login fue exitoso
            if ($response['success']) {
                $jsonResponse['token'] = $response['token'];
                $jsonResponse['user'] = $response['user'];
                
                session_start();
                $_SESSION['user_token'] = $response['token'];
                $_SESSION['user_data'] = $response['user'];
            }

            http_response_code($response['statusCode']);
            echo json_encode($jsonResponse, JSON_UNESCAPED_UNICODE);
            exit;

        default:
            throw new Exception('Opción no válida', 400);
    }

    // Validar estructura de respuesta para opciones distintas de '2'
    if (!isset($response) || !is_array($response)) {
        throw new Exception('Error en la respuesta del servicio', 500);
    }

    // Construir respuesta estándar para opciones diferentes a '2'
    $jsonResponse = [
        'success' => $response['success'] ?? false,
        'message' => $response['message'] ?? '',
        'token' => $response['token'] ?? null,
        'user' => $response['user'] ?? null,
        'error' => $response['error'] ?? null
    ];

    // Enviar respuesta
    http_response_code($response['statusCode'] ?? 200);
    echo json_encode($jsonResponse, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Manejo centralizado de errores
    $statusCode = is_numeric($e->getCode()) ? $e->getCode() : 500;
    error_log("Error en AuthAction: " . $e->getMessage());
    
    http_response_code($statusCode);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => 'SERVER_ERROR',
        'token' => null,
        'user' => null
    ], JSON_UNESCAPED_UNICODE);
}

exit;
