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
            $this->view->renderTemplate('index/register.html');
        }
        die();
    }

    public function dashboardAction() {
        $this->view->renderTemplate('index/dashboard.html');
    }

}