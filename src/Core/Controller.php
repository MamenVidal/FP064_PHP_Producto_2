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
}