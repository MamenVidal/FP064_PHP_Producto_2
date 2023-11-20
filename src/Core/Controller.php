<?php

namespace Core;

use App\Models\Auth;
use Core\View;

abstract class Controller
{
    protected $authModel;
    protected $view;
    protected $authRequired = false; // Por defecto, no se requiere autenticación

    public function __construct(
    ) {
        // Crear una instancia del modelo de Autenticación
        $this->authModel = new Auth();
        $this->view = new View();

        // Si el controlador requiere autenticación y el usuario no está logueado, redirigir a la página de inicio
        if ($this->authRequired && !$this->authModel->isUserLoggedIn()) {
            header('Location: /'); // ruta base dónde se mostrará el formulario de login
            exit;
        }
    }

    /**
     * Magic method que llama a la acción adecuada del controlador
     *
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("El método $method no ha sido encontrado en el controlador" . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return void
     */
    protected function before()
    {
    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after()
    {
    }

    /**
     * Verifica si la solicitud actual es un POST.
     *
     * @return bool
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Verifica si la solicitud actual es una solicitud Ajax.
     *
     * @return bool
     */
    protected function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Obtiene todos los parámetros de la solicitud, tanto GET como POST.
     *
     * @return array
     */
    protected function getParams()
    {
        return $_REQUEST;
    }

    /**
     * Obtiene un parámetro específico de la solicitud GET o POST.
     *
     * @param string $key La clave del parámetro
     * @return mixed El valor del parámetro o null si no está definido
     */
    protected function getParam($key)
    {
        return $_REQUEST[$key] ?? null;
    }

    protected function addFlashMessage($tipo, $texto)
    {
        if (!session_id()) {
            session_start();
        }

        if(empty($_SESSION['flash_messages'])) $_SESSION['flash_messages'] = [];

        $_SESSION['flash_messages'][] = [
            'tipo' => $tipo,
            'texto' => $texto
        ];
    }

    protected function getFlashMessages()
    {
        if (!session_id()) {
            session_start();
        }

        // Recuperar mensajes
        $messages = $_SESSION['flash_messages'] ?? [];

        // Limpiar los mensajes para que no se muestren de nuevo
        unset($_SESSION['flash_messages']);

        return $messages;
    }

    protected function onlyAuth() {
        if (!$this->authModel->isUserLoggedIn()) {
            $this->addFlashMessage('danger', "Acceso restringido.");
            header('Location: /');
            exit;
        }
    }

    protected function onlyAdmin() {
        if (!$this->isAdmin()) {
            $this->addFlashMessage('danger', "Acceso restringido a administradores.");
            header('Location: /');
            exit;
        }
    }

    protected function onlyPonente() {
        if (!$this->isPonente()) {
            $this->addFlashMessage('danger', "Acceso restringido a ponentes.");
            header('Location: /');
            exit;
        }
    }

    protected function onlyUsuario() {
        if (!$this->isUsuario()) {
            $this->addFlashMessage('danger', "Acceso restringido a usuarios.");
            header('Location: /');
            exit;
        }
    }

    public function isAdmin() {
        return $this->authModel->isUserLoggedIn() && $this->authModel->getUserData()['Id_tipo_usuario'] == 1;
    }
    public function isPonente() {
        return $this->authModel->isUserLoggedIn() && $this->authModel->getUserData()['Id_tipo_usuario'] == 2;
    }
    public function isUsuario() {
        return $this->authModel->isUserLoggedIn() && $this->authModel->getUserData()['Id_tipo_usuario'] == 3;
    }
}