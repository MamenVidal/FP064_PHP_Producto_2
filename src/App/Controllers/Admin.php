<?php

namespace App\Controllers;

use App\Models\Actos;

class Admin extends \Core\Controller
{

    public function actoEditAction() {
        $this->onlyAdmin();
        $actosModel = new Actos();
        $acto = $actosModel->load($this->getParam('id'));
        $this->view->renderTemplate('actos/edit.html', ['flash_messages' => $this->getFlashMessages(), 'acto' => $acto]);
    }
    public function actoSaveAction() {
        $this->onlyAdmin();
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
        if ($acto) {
            $_SESSION['flash_messages'][] = [
                'tipo' => 'success',
                'texto' => "Acto guardado correctamente."
            ];
            header('Location: /');
            exit;
        } else {
            $_SESSION['flash_messages'][] = [
                'tipo' => 'danger',
                'texto' => "Error al guardar el acto."
            ];
            header('Location: /');
            exit;
        }
    }

}