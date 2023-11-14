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

    public function registerPostAction() {
        
        $_SESSION['flash_messages'] = [];
        if ( !$this->authModel->isUserLoggedIn() && $this->isPost() ) {
            $data = $this->getParams();

            // validamos que existen todos los datos
            $fields = ['Nombre','Apellido1','Apellido2','Username','Password'];
            foreach($fields as $field) {
                if(empty($this->getParam($field))) {
                    $_SESSION['flash_messages'][] = [
                        'tipo' => 'danger',
                        'texto' => "El campo $field es obligatorio."
                    ];
                    header('Location: /');
                    exit() ;
                }
            }

            if( $this->usuarios->existeUsername( $this->getParam('Username') ) ) {
                $_SESSION['flash_messages'][] = [
                    'tipo' => 'danger',
                    'texto' => "Ya existe un usuario registrado con el mismo username"
                ];
                header('Location: /');
                exit() ;
            }

            // creamos persona
            $persona = $this->personas->save(null,[
                'Nombre' => $this->getParam('Nombre'),
                'Apellido1' => $this->getParam('Apellido1'),
                'Apellido2' => $this->getParam('Apellido2'),
            ]);
            if(!$persona) {
                $_SESSION['flash_messages'][] = [
                    'tipo' => 'danger',
                    'texto' => "Se ha producido un error al crear la persona."
                ];
                header('Location: /');
                exit() ;
            }

            // creamos usuario
            $this->usuarios->register($this->getParam('Username'), $this->getParam('Password'), $persona['Id_persona'], TiposUsuarios::DEFAULT_TIPO_USUARIO);
            if(!$persona) {
                $_SESSION['flash_messages'][] = [
                    'tipo' => 'danger',
                    'texto' => "Se ha producido un error al crear el usuario."
                ];
                header('Location: /');
                exit() ;
            } else {
                // como el registro ha sido correcto lo logueamos
                $login = $this->auth->login($this->getParam('Username'), $this->getParam('Password'));
                if($login) {
                    $_SESSION['flash_messages'][] = [
                        'tipo' => 'success',
                        'texto' => "Se ha registrado correctamente."
                    ];
                } else  {
                    $_SESSION['flash_messages'][] = [
                        'tipo' => 'info',
                        'texto' => "Su usuario se ha registrado correctamente, por favor haga login."
                    ];
                }
            }
            
        }
        header('Location: /');
        exit;
    }

    public function loginPostAction() {
        
        $_SESSION['flash_messages'] = [];
        if ( !$this->authModel->isUserLoggedIn() && $this->isPost() ) {
            $data = $this->getParams();

            // validamos que existen todos los datos
            $fields = ['Username','Password'];
            foreach($fields as $field) {
                if(empty($this->getParam($field))) {
                    $_SESSION['flash_messages'][] = [
                        'tipo' => 'danger',
                        'texto' => "El campo $field es obligatorio."
                    ];
                    header('Location: /');
                    exit() ;
                }
            }

            if( !$this->usuarios->existeUsername( $this->getParam('Username') ) ) {
                $_SESSION['flash_messages'][] = [
                    'tipo' => 'danger',
                    'texto' => "No existe un usuario registrado con este username"
                ];
                header('Location: /');
                exit() ;
            }

            // logueamos
            $login = $this->auth->login($this->getParam('Username'), $this->getParam('Password'));
            if($login) {
                $_SESSION['flash_messages'][] = [
                    'tipo' => 'success',
                    'texto' => "Se ha logueado correctamente."
                ];
            } else  {
                $_SESSION['flash_messages'][] = [
                    'tipo' => 'danger',
                    'texto' => "La contraseña es incorrecta."
                ];
            }
            
        }
        header('Location: /');
        exit;
    }

    public function logoutAction() {
        
        $this->auth->logout();
        $_SESSION['flash_messages'] = [];
        $_SESSION['flash_messages'][] = [
            'tipo' => 'success',
            'texto' => "Ha cerrado sesión correctamente."
        ];
        header('Location: /');
        exit;
    }
}