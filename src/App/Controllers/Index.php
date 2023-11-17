<?php

namespace App\Controllers;

use App\Models\Actos;

class Index extends \Core\Controller
{

    public function indexAction() {
        if ($this->authModel->isUserLoggedIn()) {
            // Obtener datos de usuario si es necesario
            $userData = $this->authModel->getUserData();
            $actosModel = new Actos();
            $actos = $actosModel->all();
            // Mostrar una vista o texto para usuarios autenticados
            $this->view->renderTemplate(
                'index/dashboard.html', 
                [
                    'flash_messages' => $this->getFlashMessages(), 
                    'user' => $userData,
                    'actos' => $actos,
                ]
            );
        } else {
            // Mostrar una vista o texto para usuarios no autenticados
            $this->view->renderTemplate('index/register.html', ['flash_messages' => $this->getFlashMessages()]);
        }
        die();
    }

    public function dashboardAction() {
        $userData = $this->authModel->getUserData();
        $this->view->renderTemplate('index/dashboard.html', ['flash_messages' => $this->getFlashMessages(), 'user' => $userData]);
    }

}