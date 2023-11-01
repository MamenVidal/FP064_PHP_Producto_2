<?php

namespace Core;

class Router
{
    
    /**
     * Array de routes declarados
     */
    protected $routes = [];

    /**
     * Función para declarar una ruta y mapear el controlador y la acción
     */
    public function addRoute($route,$controller,$action) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Función para recuperar todas las rutas
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Función que recibida una url, si existe una ruta, ejecuta la acción del controlador
     */
    public function dispatch($url)
    {
        // obtenemos la url sin parámetros
        $url = $this->removeQueryStringVariables($url);
        // si existe ruta para la url
        if ($params = $this->match($url)) {
            // calculamos la clase del controlador
            $controller = $params['controller'];
            $controllerClassname = 'App\Controllers\\' . $controller;
            $action = $params['action'];
            $actionMethod = $action.'Action';
            // si existe clase del contrlador
            if (class_exists($controllerClassname)) {
                // instanciamos la clase del controlador
                $controllerObject = new $controllerClassname();

                // si el método existe lo llamamos
                if (method_exists($controllerObject, $actionMethod)) {
                    call_user_func_array([$controllerObject, $actionMethod], []);
                } else {
                    throw new \Exception("El método $actionMethod no ha sido encontrado en el controlador" . get_class($this));
                }
            } else {
                throw new \Exception("El controlador $controller no existe",404);
            }
        } else {
            throw new \Exception('La ruta solicita no existe', 404);
        }
    }

    /**
     * Función que extrae la parte del path de la url
     */
    protected function removeQueryStringVariables($url)
    {
        if ( !empty($url) ) {
            $parts = explode('&', $url, 2);
            if ( strpos($parts[0], '=') === false ) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }
    
    /**
     * Función para recuperar el controlador y acción vinculados a una ruta
     */
    public function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (empty($url) && empty($route)) {
                return $params;
            }
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                return $params;
            }
        }
    }

}