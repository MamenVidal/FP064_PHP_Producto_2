<?php
namespace App\Models;

use App\Models\Usuarios;

class Auth {

    public function __construct() {
        // Asegurarse de que la sesión esté iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password) {
        // Implementar la lógica de login aquí usando UsuarioModel
        $usuarioModel = new \App\Models\Usuarios();
        $user = $usuarioModel->login($username, $password);

        if ($user) {
            // Almacenar información relevante en la sesión
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_data'] = $user;
            return true;
        }

        return false;
    }

    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = [];
        // Si se desea destruir la sesión completamente, borrar también la cookie de sesión.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        // Finalmente, destruir la sesión
        session_destroy();
    }

    public function isUserLoggedIn() {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
    }

    public function getUserData() {
        return $_SESSION['user_data'] ?? null;
    }

}
