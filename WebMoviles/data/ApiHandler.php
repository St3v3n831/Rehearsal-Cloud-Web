<?php

class ApiHandler
{
    // URL base del backend
    private const BASE_URL = "http://localhost:5198/api";

    /**
     * Realiza una petición HTTP
     * 
     * @param string $method El método HTTP (GET, POST, DELETE)
     * @param string $endpoint El endpoint relativo (por ejemplo, 'Auth/register')
     * @param array|null $data Los datos para enviar en la solicitud (para POST/PUT)
     * @return array La respuesta del backend
     */
    private function makeRequest(string $method, string $endpoint, ?array $data = null): array
    {
        $url = self::BASE_URL . '/' . $endpoint;
        $curl = curl_init($url);

        $headers = ['Content-Type: application/json'];

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'status_code' => $httpCode,
            'response' => json_decode($response, true)
        ];
    }

    /**
     * Registra un usuario
     * 
     * @param array $user Datos del usuario
     * @return array Respuesta del backend
     */
    public function registerUser(array $user): array
    {
        return $this->makeRequest('POST', 'Auth/register', $user);

    }

    /**
     * Inicia sesión con un usuario
     * 
     * @param array $credentials Credenciales del usuario
     * @return array Respuesta del backend
     */
    public function loginUser(array $credentials): array
    {
        return $this->makeRequest('POST', 'Auth/login', $credentials);
    }

    /**
     * Obtiene la lista de usuarios
     * 
     * @return array Respuesta del backend
     */
    public function getUsers(): array
    {
        return $this->makeRequest('GET', 'Auth/users');
    }

    /**
     * Elimina un usuario por ID
     * 
     * @param int $id ID del usuario a eliminar
     * @return array Respuesta del backend
     */
    public function deleteUser(int $id): array
    {
        return $this->makeRequest('DELETE', "Auth/users/$id");
    }
}


