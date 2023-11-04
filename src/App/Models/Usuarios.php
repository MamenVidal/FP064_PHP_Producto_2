<?php

namespace App\Models;

class Usuarios extends \Core\Model
{
    protected $table = 'Usuarios';
    protected $pkField = 'Id_usuario';

    protected function defineTable(): string {
        return $this->table;
    }

    protected function definePkField(): string {
        return $this->pkField;
    }

    // Método para registrar un nuevo usuario
    public function register($username, $password, $idPersona, $tipoUsuario) {
        // Cifrar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Crear el array de datos para el nuevo usuario
        $data = [
            'Username' => $username,
            'Password' => $hashedPassword,
            'Id_Persona' => $idPersona,
            'Id_tipo_usuario' => $tipoUsuario
        ];
        
        // Guardar el nuevo usuario y devolver el resultado
        return $this->save(null, $data);
    }

    // Método para verificar las credenciales de un usuario
    public function login($username, $password) {
        // Buscar al usuario por su nombre de usuario
        $user = $this->query("SELECT * FROM {$this->table} WHERE Username = :username", ['username' => $username]);
        
        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && password_verify($password, $user[0]['Password'])) {
            // Devolver los datos del usuario, excepto la contraseña
            unset($user[0]['Password']);
            return $user[0];
        }
        
        // Credenciales incorrectas
        return false;
    }

    // Método para actualizar los datos de un usuario
    public function updateProfile($idUsuario, $username, $password, $idPersona, $tipoUsuario) {
        // Cifrar la nueva contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Crear el array de datos para actualizar
        $data = [
            'Username' => $username,
            'Password' => $hashedPassword,
            'Id_Persona' => $idPersona,
            'Id_tipo_usuario' => $tipoUsuario
        ];
        
        // Actualizar el usuario y devolver el resultado
        return $this->save($idUsuario, $data);
    }

}