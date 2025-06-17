<?php
include_once __DIR__ . '/../data/ApiHandler.php';
include_once __DIR__ . '/../domain/Auth.php';

class AuthBusiness
{
    private $apiHandler;

    public function __construct()
    {
        $this->apiHandler = new ApiHandler();
    }

    // Registrar un nuevo usuario
    public function registerUser(Auth $user)
    {
        $data = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ];

        $result = $this->apiHandler->registerUser($data);

        // Ajustar para manejar la respuesta del backend .NET
        if ($result['status_code'] === 200) {
            return [
                'success' => true,
                'message' => $result['response']['message'] ?? 'Usuario registrado exitosamente',
                'data' => $result['response'] ?? [],
                'statusCode' => $result['status_code']
            ];
        } elseif ($result['status_code'] === 400) {
            return [
                'success' => false,
                'message' => $result['response']['message'] ?? 'Error en el registro',
                'error' => 'VALIDATION_ERROR',
                'statusCode' => $result['status_code']
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error en el servicio de registro',
                'error' => 'SERVICE_ERROR',
                'statusCode' => $result['status_code'] ?? 500
            ];
        }
    }

    public function loginUser(Auth $user)
    {
        $data = [
            'username' => $user->getUsername(),
            'password' => $user->getPassword()
        ];

        $result = $this->apiHandler->loginUser($data);

        // Adaptación para coincidir con .NET/Kotlin
        if ($result['status_code'] === 200) {
            return [
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'token' => $result['response']['token'] ?? null,
                'user' => [
                    'id' => $result['response']['user']['id'] ?? null,
                    'username' => $user->getUsername()
                ],
                'statusCode' => 200
            ];
        } elseif ($result['status_code'] === 400) {
            // Manejo de errores como en .NET
            $errorResponse = $result['response'];
            return [
                'success' => false,
                'message' => $errorResponse['message'] ?? 'Error de autenticación',
                'error' => $errorResponse['error'] ?? 'AUTH_ERROR',
                'statusCode' => 400
            ];
        } else {
            // Otros errores HTTP
            return [
                'success' => false,
                'message' => 'Error en el servidor',
                'error' => 'SERVER_ERROR',
                'statusCode' => $result['status_code']
            ];
        }
    }

    public function getUsers()
    {
        $result = $this->apiHandler->getUsers();

        var_dump($result);
        die();

        if ($result['status_code'] === 200 && isset($result['response']['data'])) {
            return $result['response']['data']; // Devuelve directamente los datos
        } else {
            // Registra el error para diagnóstico
            error_log('Error en getUsers: ' . print_r($result, true));
            throw new Exception('Error al obtener usuarios', $result['status_code']);
        }
    }


    public function deleteUser(int $id)
    {
        $result = $this->apiHandler->deleteUser($id);

        if ($result['status_code'] === 200 && isset($result['response']['success'])) {
            return [
                'success' => $result['response']['success'],
                'message' => $result['response']['message'] ?? ($result['response']['success'] ? 'Usuario eliminado' : 'Error al eliminar'),
                'error' => $result['response']['error'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error en el servicio al eliminar usuario',
                'error' => $result['response']['error'] ?? 'DELETE_ERROR'
            ];
        }
    }
}
?>
