<?php

namespace App\Controllers;

use App\Models\Actos;

class Admin extends \Core\Controller
{

    // Método para editar un acto existente
    public function actoEditAction() {
        $this->onlyAdmin();  // Acceso restringido solo a administradores
        // Carga el modelo de Actos y obtiene un acto específico por su ID
        $actosModel = new Actos();
        $acto = $actosModel->load($this->getParam('id'));
        $this->view->renderTemplate('actos/edit.html', ['flash_messages' => $this->getFlashMessages(), 'acto' => $acto]);
    }
    
    // Método para guardar o actualizar un acto
    public function actoSaveAction() {
        $this->onlyAdmin();
         // Crea una nueva instancia del modelo Actos y guarda la info del acto
        $actosModel = new Actos();
        $acto = $actosModel->save($this->getParam('Id_acto'),[
            'Titulo' => $this->getParam('Titulo'),
            'Descripcion_corta' => $this->getParam('Descripcion_corta'),
            'Fecha' => $this->getParam('Fecha'),
            'Hora' => $this->getParam('Hora'),
            'Num_asistentes' => $this->getParam('Num_asistentes'),
            'Id_tipo_acto' => $this->getParam('Id_tipo_acto'),
            'Descripcion_larga' => $this->getParam('Descripcion_larga'),
        ]);
        // Verificar que el acto se guardó correctamente
        if ($acto) {
            $this->addFlashMessage('success', "Acto guardado correctamente.");
            header('Location: /');
            exit;
        } else {
            $this->addFlashMessage('danger', "Error al guardar el acto.");
            header('Location: /');
            exit;
        }
    }

}