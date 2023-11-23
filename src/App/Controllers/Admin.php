<?php

namespace App\Controllers;

use App\Models\Actos;
use App\Models\ListaPonentes;
use App\Models\TipoActo;
use App\Models\Personas;
use App\Models\Inscritos;

class Admin extends \Core\Controller
{

    // Método para editar un acto existente
    public function actoEditAction() {
        $this->onlyAdmin();  // Acceso restringido solo a administradores
        
        $userData = $this->authModel->getUserData();

        // Carga el modelo de Actos y obtiene un acto específico por su ID
        $actosModel = new Actos();
        $acto = $actosModel->load($this->getParam('id'));
        $tipoActoModel = new TipoActo();
        $tiposActo = $tipoActoModel->all();
        $this->view->renderTemplate(
            'actos/edit.html', 
            [
                'flash_messages' => $this->getFlashMessages(), 
                'user' => $userData,
                'acto' => $acto,
                'tipo_acto' => $tiposActo,
            ]
        );
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
            header('Location: ' . \Core\View::BASE_PATH);
            exit;
        } else {
            $this->addFlashMessage('danger', "Error al guardar el acto.");
            header('Location: ' . \Core\View::BASE_PATH);
            exit;
        }
    }

    // Método para eliminar un acto
    public function actoDeleteAction() {
        $this->onlyAdmin(); // Acceso restringido solo a administradores
        // Crea una nueva instancia del modelo Actos y elimina el acto
        $actosModel = new Actos();
        try {
            $result = $actosModel->delete($this->getParam('id'));
        } catch (\Exception $e) {
            $this->addFlashMessage('danger', "Error al eliminar el acto. Revisa que no tenga Invitados o ponentes asociados.");
            header('Location: ' . \Core\View::BASE_PATH);
            exit;
        }
        // Verificar que el acto se eliminó correctamente
        if ($result) {
            $this->addFlashMessage('success', "Acto eliminado correctamente.");
        } else {
            $this->addFlashMessage('danger', "Error al eliminar el acto.");
        }
    
        header('Location: ' . \Core\View::BASE_PATH . 'ruta-a-listado-actos');
        exit;
    }

    // Método para editar un tipo de acto existente
    public function tipoActoEditAction() {
        $this->onlyAdmin();  // Acceso restringido solo a administradores
        
        $userData = $this->authModel->getUserData();

        // Carga el modelo de TipoActo y obtiene un tipo de acto específico por su ID
        $tipoActoModel = new TipoActo();
        $tipoActo = $tipoActoModel->load($this->getParam('id'));
        $this->view->renderTemplate(
            'tipo_acto/edit.html', 
            [
                'flash_messages' => $this->getFlashMessages(), 
                'user' => $userData,
                'tipo_acto' => $tipoActo,
            ]
        );
    }

    // Método para guardar o actualizar un tipo de acto
    public function tipoActoSaveAction() {
        $this->onlyAdmin();
         // Crea una nueva instancia del modelo TipoActo y guarda la info del tipo de acto
        $tipoActoModel = new TipoActo();
        $tipoActo = $tipoActoModel->save($this->getParam('Id_tipo_acto'),[
            'Descripcion' => $this->getParam('Descripcion'),
        ]);
        // Verificar que el tipo de acto se guardó correctamente
        if ($tipoActo) {
            $this->addFlashMessage('success', "Tipo de acto guardado correctamente.");
            header('Location: ' . \Core\View::BASE_PATH);
            exit;
        } else {
            $this->addFlashMessage('danger', "Error al guardar el tipo de acto.");
            header('Location: ' . \Core\View::BASE_PATH);
            exit;
        }
    }

    // Método para eliminar un tipo de acto
    public function tipoActoDeleteAction() {
        $this->onlyAdmin();
        // Crea una nueva instancia del modelo TipoActo y elimina el tipo de acto
        $tipoActoModel = new TipoActo();
        try {
            $tipoActo = $tipoActoModel->delete($this->getParam('id'));
        } catch (\Exception $e) {
            $this->addFlashMessage('danger', "Error al eliminar el tiipo de acto. Revisa que no este asociado a un acto existente.");
            header('Location: ' . \Core\View::BASE_PATH);
            exit;
        }
        // Verificar que el tipo de acto se eliminó correctamente
        if ($tipoActo) {
            $this->addFlashMessage('success', "Tipo de acto eliminado correctamente.");
            header('Location: ' . \Core\View::BASE_PATH);
            exit;
        } else {
            $this->addFlashMessage('danger', "Error al eliminar el tipo de acto.");
            header('Location: ' . \Core\View::BASE_PATH);
            exit;
        }
    }

    // Método para listar los ponentes de un acto
    public function ponenteListAction() {
        $this->onlyAdmin();
        $userData = $this->authModel->getUserData();
        // Crea una nueva instancia del modelo Actos y obtiene los ponentes de un acto
        $ponentesModel = new ListaPonentes();
        $ponentes = $ponentesModel->getPonentes($this->getParam('id'));
        $this->view->renderTemplate(
            'ponentes/index.html', 
            [
                'flash_messages' => $this->getFlashMessages(), 
                'user' => $userData,
                'ponentes' => $ponentes,
            ]
        );
    }

    // Método para añadir un ponente a un acto
    public function ponenteAddAction() {
        $this->onlyAdmin();
        $userData = $this->authModel->getUserData();

        $actosModel = new Actos();
        $listaActos = $actosModel->all();
        $personasModel = new Personas();
        $listaPersonas = $personasModel->all();
        
        $this->view->renderTemplate(
            'ponentes/add.html', 
            [
                'flash_messages' => $this->getFlashMessages(), 
                'user' => $userData,
                'actos' => $listaActos,
                'personas' => $listaPersonas,
            ]
        );
    }

    // Método para añadir un ponente a un acto
    public function ponenteSaveAction() {
        $this->onlyAdmin();
        // Crea una nueva instancia del modelo Actos y añade un ponente a un acto
        $actosModel = new Actos();
        $acto = $actosModel->addPonente($this->getParam('Id_acto'), $this->getParam('Id_persona'), $this->getParam('Orden'));
        // Verificar que el ponente se añadió correctamente
        if ($acto) {
            $this->addFlashMessage('success', "Ponente añadido correctamente.");
            header('Location: ' . \Core\View::BASE_PATH . 'ponente-list');
            exit;
        }
        $this->addFlashMessage('danger', "Error al añadir el ponente.");
        header('Location: ' . \Core\View::BASE_PATH . 'ponente-list');
        exit;
    }

    // Método para eliminar un ponente de un acto
    public function ponenteRemoveAction() {
        $this->onlyAdmin();
        // Crea una nueva instancia del modelo Actos y elimina un ponente de un acto
        $actosModel = new Actos();
        $acto = $actosModel->removePonente($this->getParam('id'));
        // Verificar que el ponente se eliminó correctamente
        if ($acto) {
            $this->addFlashMessage('success', "Ponente eliminado correctamente.");
            header('Location: ' . \Core\View::BASE_PATH . 'ponente-list');
            exit;
        }
        $this->addFlashMessage('danger', "Error al eliminar el ponente.");
        header('Location: ' . \Core\View::BASE_PATH . 'ponente-list');
        exit;
    }

    // Método para listar los inscritos a un acto
    public function inscritosListAction() {
        $this->onlyAdmin();
        $userData = $this->authModel->getUserData();
        
        $inscritosModel = new Inscritos();
        $inscritos = $inscritosModel->getInscritos($this->getParam('id'));
        $this->view->renderTemplate(
            'inscritos/index.html', 
            [
                'flash_messages' => $this->getFlashMessages(), 
                'user' => $userData,
                'inscritos' => $inscritos,
            ]
        );
    }

    // Método para añadir un ponente a un acto
    public function inscritosAddAction() {
        $this->onlyAdmin();
        $userData = $this->authModel->getUserData();

        $actosModel = new Actos();
        $listaActos = $actosModel->all();
        $personasModel = new Personas();
        $listaPersonas = $personasModel->all();
        
        $this->view->renderTemplate(
            'inscritos/add.html', 
            [
                'flash_messages' => $this->getFlashMessages(), 
                'user' => $userData,
                'actos' => $listaActos,
                'personas' => $listaPersonas,
            ]
        );
    }

    // Método para añadir un usuario a un acto
    public function inscritosSaveAction() {
        $this->onlyAdmin();
        // Crea una nueva instancia del modelo Actos y añade un usuario a un acto
        $actosModel = new Actos();
        $acto = $actosModel->addInscrito($this->getParam('Id_acto'), $this->getParam('Id_persona'));
        // Verificar que el usuario se añadió correctamente
        if ($acto) {
            $this->addFlashMessage('success', "Usuario añadido correctamente.");
            header('Location: ' . \Core\View::BASE_PATH . 'inscritos-list');
            exit;
        } else {
            $this->addFlashMessage('danger', "Error al añadir el usuario.");
            header('Location: ' . \Core\View::BASE_PATH . 'inscritos-list');
            exit;
        }
    }

    // Método para eliminar un usuario de un acto
    public function inscritosRemoveAction() {
        $this->onlyAdmin();
        // Crea una nueva instancia del modelo Actos y elimina un usuario de un acto
        $actosModel = new Actos();
        $acto = $actosModel->removeInscrito($this->getParam('id'));
        // Verificar que el usuario se eliminó correctamente
        if ($acto) {
            $this->addFlashMessage('success', "Usuario eliminado correctamente.");
            header('Location: ' . \Core\View::BASE_PATH . 'inscritos-list');
            exit;
        } else {
            $this->addFlashMessage('danger', "Error al eliminar el usuario.");
            header('Location: ' . \Core\View::BASE_PATH . 'inscritos-list');
            exit;
        }
    }

}