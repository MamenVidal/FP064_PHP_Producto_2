<?php

namespace App\Controllers;

class Index extends \Core\Controller
{

    public function indexAction() {
        if ($this->authModel->isUserLoggedIn()) {
            // Obtener datos de usuario si es necesario
            $userData = $this->authModel->getUserData();
            // Mostrar una vista o texto para usuarios autenticados
            $this->view->renderTemplate('index/dashboard.html');
        } else {
            // Mostrar una vista o texto para usuarios no autenticados
            $this->view->renderTemplate('index/dashboard.html');

            // IMPORTANTE CAMBIAR EL SEGUNDO RENDERTEMPLATE A REGISTER.HTML 
            // CUANDO TENGAMOS LA LÓGICA DEL LOGIN IMPLEMENTADA.
            // SE HA QUITADO PORQUE REDIRIGIA TODO EL RATO A REGISTER PORQUE AUN
            // NO TENEMOS UN USUARIO LOGEADO.

        }
        die();
    }

    public function dashboardAction() {
        $this->view->renderTemplate('index/dashboard.html');
    }

}