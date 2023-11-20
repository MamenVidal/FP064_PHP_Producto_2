<?php

namespace App\Controllers;

use App\Models\Personas;
use App\Models\Usuarios;
use App\Models\TiposUsuarios;
use App\Models\Auth;

class User extends \Core\Controller
{

    private Personas $personas;
    private Usuarios $usuarios;
    private TiposUsuarios $tiposUsuarios;
    private Auth $auth;

    public function __construct(
    ) {
        $this->personas = new Personas();
        $this->usuarios = new Usuarios();
        $this->tiposUsuarios = new TiposUsuarios();
        $this->auth = new Auth();
        parent::__construct();
    }

    public function userEditAction() {
        $this->onlyAuth();
        $this->view->renderTemplate('user/edit.html', ['flash_messages' => $this->getFlashMessages(), 'user' => $this->auth->getUserData()]);
    }

    public function userSaveAction() {
        $this->onlyAuth();
        // validamos que existen todos los datos
        $requiredFields = ['Username','Nombre','Apellido1','Apellido2'];
        foreach($requiredFields as $field) {
            if(empty($this->getParam($field))) {
                $this->addFlashMessage('danger', "El campo $field es obligatorio.");
                header('Location: ' . \Core\View::BASE_PATH . 'user-edit');
                exit() ;
            }
        }

        // validamos si se está cambiando el password
        if( empty($this->getParam('Password')) ) {
            $this->usuarios->save($this->auth->getUserData()['Id_usuario'], [
                'Username' => $this->getParam('Username'),
            ]);
        } else {
            // Cifrar la contraseña
            $hashedPassword = password_hash($this->getParam('Password'), PASSWORD_DEFAULT);
            $this->usuarios->save($this->auth->getUserData()['Id_usuario'], [
                'Username' => $this->getParam('Username'),
                'Password' => $hashedPassword,
            ]);
        }

        // actualizamos persona
        $this->personas->save($this->auth->getUserData()['Id_usuario'], [
            'Nombre' => $this->getParam('Nombre'),
            'Apellido1' => $this->getParam('Apellido1'),
            'Apellido2' => $this->getParam('Apellido2'),
        ]);

        $this->auth->refreshUserData();

        $this->addFlashMessage('success', "Se ha actualizado correctamente.");

        header('Location: ' . \Core\View::BASE_PATH . 'user-edit');
    }

    public function registerPostAction() {
        
        if ( !$this->authModel->isUserLoggedIn() && $this->isPost() ) {
            $data = $this->getParams();

            // validamos que existen todos los datos
            $fields = ['Nombre','Apellido1','Apellido2','Username','Password'];
            foreach($fields as $field) {
                if(empty($this->getParam($field))) {
                    $this->addFlashMessage('danger', "El campo $field es obligatorio.");
                    header('Location: ' . \Core\View::BASE_PATH);
                    exit() ;
                }
            }

            if( $this->usuarios->existeUsername( $this->getParam('Username') ) ) {
                $this->addFlashMessage('danger', "Ya existe un usuario registrado con el mismo username");
                header('Location: ' . \Core\View::BASE_PATH);
                exit() ;
            }

            // creamos persona
            $persona = $this->personas->save(null,[
                'Nombre' => $this->getParam('Nombre'),
                'Apellido1' => $this->getParam('Apellido1'),
                'Apellido2' => $this->getParam('Apellido2'),
            ]);
            if(!$persona) {
                $this->addFlashMessage('danger', "Se ha producido un error al crear la persona.");
                header('Location: ' . \Core\View::BASE_PATH);
                exit() ;
            }

            // creamos usuario
            $this->usuarios->register($this->getParam('Username'), $this->getParam('Password'), $persona['Id_persona'], TiposUsuarios::DEFAULT_TIPO_USUARIO);
            if(!$persona) {
                $this->addFlashMessage('danger', "Se ha producido un error al crear el usuario.");
                header('Location: ' . \Core\View::BASE_PATH);
                exit() ;
            } else {
                // como el registro ha sido correcto lo logueamos
                $login = $this->auth->login($this->getParam('Username'), $this->getParam('Password'));
                if($login) {
                    $this->addFlashMessage('success', "Se ha registrado correctamente.");
                } else  {
                    $this->addFlashMessage('danger', "Se ha producido un error al loguear el usuario.");
                }
            }
            
        }
        header('Location: ' . \Core\View::BASE_PATH);
        exit;
    }

    public function loginPostAction() {
        
        if ( !$this->authModel->isUserLoggedIn() && $this->isPost() ) {
            $data = $this->getParams();

            // validamos que existen todos los datos
            $fields = ['Username','Password'];
            foreach($fields as $field) {
                if(empty($this->getParam($field))) {
                    $this->addFlashMessage('danger', "El campo $field es obligatorio.");
                    header('Location: ' . \Core\View::BASE_PATH);
                    exit() ;
                }
            }

            if( !$this->usuarios->existeUsername( $this->getParam('Username') ) ) {
                $this->addFlashMessage('danger', "No existe un usuario registrado con este username");
                header('Location: ' . \Core\View::BASE_PATH);
                exit() ;
            }

            // logueamos
            $login = $this->auth->login($this->getParam('Username'), $this->getParam('Password'));
            if($login) {
                $this->addFlashMessage('success', "Se ha logueado correctamente.");
            } else  {
                $this->addFlashMessage('danger', "Se ha producido un error al loguear el usuario.");
            }
            
        }
        header('Location: ' . \Core\View::BASE_PATH);
        exit;
    }

    public function logoutAction() {
        
        $this->auth->logout();
        $this->addFlashMessage('success', "Ha cerrado sesión correctamente.");
        header('Location: ' . \Core\View::BASE_PATH);
        exit;
    }
}