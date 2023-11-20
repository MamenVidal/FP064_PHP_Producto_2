<?php
namespace App\Models;


use App\Models\Personas;
use App\Models\TiposUsuarios;
use App\Models\Usuarios;

class Auth {

    private Personas $personas;
    private TiposUsuarios $tiposUsuarios;
    private Usuarios $usuarios;

    public function __construct(
    ) {
        $this->personas = new Personas();
        $this->tiposUsuarios = new TiposUsuarios();
        $this->usuarios = new Usuarios();
        // Asegurarse de que la sesión esté iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password) {
        // Implementar la lógica de login aquí usando UsuarioModel
        $user = $this->usuarios->login($username, $password);

        if ($user) {
            // Almacenar información relevante en la sesión
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_data'] = $user;
            return true;
        }

        return false;
    }

    public function refreshUserData() {
        $userId = $this->getUserData()['Id_usuario'];
        // Implementar la lógica de login aquí usando UsuarioModel
        $user = $this->usuarios->query("SELECT U.*,P.Nombre, P.Apellido1, P.Apellido2 FROM Usuarios U INNER JOIN Personas P ON U.Id_usuario = P.Id_persona WHERE U.Id_usuario = :idusuario", ['idusuario' => $userId]);
        if ($user) {
            // Almacenar información relevante en la sesión
            $_SESSION['user_data'] = $user[0];
        }
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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function isUserLoggedIn() {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
    }

    public function getUserData() {
        return $_SESSION['user_data'] ?? null;
    }

}
