<?php

namespace App\Controllers;

use App\Models\Actos;

class Calendario extends \Core\Controller
{

    public function indexAction() {
        $this->onlyAuth();

        // Obtener datos de usuario si es necesario
        $userData = $this->authModel->getUserData();
        $idPersona = $userData['Id_usuario'];

        // obtener todos los actos del calendario incluyendo la información de inscripción
        $actosModel = new Actos();
        $actosWithInscripciones = $actosModel->allWithIncripcion($idPersona);

        $eventos = [];
        foreach($actosWithInscripciones as &$acto) {
            $description = "
                <div><strong>Título:</strong> {$acto['Titulo']}</div>
                <div><strong>Fecha/Hora:</strong> {$acto['Fecha']} {$acto['Hora']}</div>
                <div><strong>Descripción corta:</strong> {$acto['Descripcion_corta']}</div>
                <div><strong>Descripción larga:</strong> {$acto['Descripcion_larga']}</div>
                <div><strong>Aforo:</strong> {$acto['Num_asistentes']}</div>
                <div><strong>Tipo de acto:</strong> {$acto['Id_tipo_acto']}</div><br>
                ";
            if($acto['Id_ponente']) {
                $eventos[] = [
                    'color' => '#FF8000',
                    'title' => $acto['Titulo'],
                    'description' => $description,
                    'description2' => "ERES PONENTE",
                    'url' => "",
                    'start' => "{$acto['Fecha']}T{$acto['Hora']}",
                ];
            } else if($acto['Id_persona']) {
                $eventos[] = [
                    'color' => '#00ff00',
                    'title' => $acto['Titulo'],
                    'description' => $description,
                    'description2' => "Clic para desuscribirte",
                    'url' => "/desuscripcion?id={$acto['Id_acto']}",
                    'start' => "{$acto['Fecha']}T{$acto['Hora']}",
                ];
            } else {
                $eventos[] = [
                    'color' => '#ff0000',
                    'title' => $acto['Titulo'],
                    'description' => $description,
                    'description2' => "Clic para suscribirte",
                    'url' => "/inscripcion?id={$acto['Id_acto']}",
                    'start' => "{$acto['Fecha']}T{$acto['Hora']}",
                ];
            }
        }

        $htmlEventos = json_encode($eventos);

        // Mostrar una vista o texto para usuarios autenticados
        $this->view->renderTemplate(
            'index/calendario.html', 
            [
                'flash_messages' => $this->getFlashMessages(), 
                'user' => $userData,
                'actos' => $htmlEventos,
            ]
        );
    }

    public function inscripcionAction() {
        $this->onlyAuth();

        // Obtener datos de usuario si es necesario
        $userData = $this->authModel->getUserData();
        $idPersona = $userData['Id_usuario'];

        // obtener el acto del calendario
        $idActo = $this->getParam('id');
        $actosModel = new Actos();
        $acto = $actosModel->load($idActo);

        // comprobar si hay plazas disponibles
        $inscritosModel = new \App\Models\Inscritos();
        $nunmInscritos = $inscritosModel->getNumInscritos($idActo);
        if($nunmInscritos >= $acto['Num_asistentes']) {
            $this->addFlashMessage('danger', "No hay plazas disponibles.");
            header('Location: ' . \Core\View::BASE_PATH . 'calendario');
            return;
        }

        // comprobar si ya está inscrito e inscribir
        $inscripcion = $inscritosModel->save($idActo, $idPersona);
        if($inscripcion) {
            $this->addFlashMessage('success', "Inscripción realizada correctamente.");
        } else {
            $this->addFlashMessage('danger', "Error al realizar la inscripción.");
        }
        header('Location: ' . \Core\View::BASE_PATH . 'calendario');
    }

    public function desuscripcionAction() {
        $this->onlyAuth();

        // Obtener datos de usuario si es necesario
        $userData = $this->authModel->getUserData();
        $idPersona = $userData['Id_usuario'];

        // obtener todos los actos del calendario incluyendo la información de inscripción
        $idActo = $this->getParam('id');
        $inscritosModel = new \App\Models\Inscritos();
        $existeInscripcion = $inscritosModel->existeInscripcion($idActo, $idPersona);
        if($existeInscripcion) {
            $idInscripcion = $existeInscripcion['Id_inscripcion'];
            $inscripcion = $inscritosModel->delete($idInscripcion);
            if($inscripcion) {
                $this->addFlashMessage('success', "Desuscripción realizada correctamente.");
            } else {
                $this->addFlashMessage('danger', "Error al realizar la desuscripción.");
            }
        } else {
            $this->addFlashMessage('danger', "Error al realizar la desuscripción.");
        }
        header('Location: ' . \Core\View::BASE_PATH . 'calendario');
    }

}