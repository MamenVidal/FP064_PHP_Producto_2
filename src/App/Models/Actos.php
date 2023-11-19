<?php

namespace App\Models;

class Actos extends \Core\Model
{
    protected $table = 'Actos';
    protected $pkField = 'Id_acto';

    protected function defineTable(): string {
        return $this->table;
    }

    protected function definePkField(): string {
        return $this->pkField;
    }

    public function allWithIncripcion($idPersona) {
        $sql = "
            SELECT a.*, i.Id_persona, ta.Descripcion as Id_tipo_acto
            FROM {$this->table} a 
            LEFT JOIN Inscritos i ON a.Id_acto = i.Id_acto AND i.Id_persona = :idPersona 
            LEFT JOIN Tipo_acto ta ON a.Id_tipo_acto = ta.Id_tipo_acto";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idPersona' => $idPersona]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function allAdmin() {
        $sql = "
            SELECT a.*, ta.Descripcion as tipo_acto
            FROM {$this->table} a 
            LEFT JOIN Tipo_acto ta ON a.Id_tipo_acto = ta.Id_tipo_acto";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addPonente($idActo, $idPersona, $orden) {
        $ponenteModel = new ListaPonentes();
        return $ponenteModel->save(null, ['Id_acto' => $idActo, 'Id_persona' => $idPersona, 'Orden' => $orden]);
    }

    public function removePonente($idPonente) {
        $ponenteModel = new ListaPonentes();
        return $ponenteModel->delete($idPonente);
    }

    public function getInscritos($idActo) {
        $sql = "
            SELECT p.Id_persona, p.Nombre, p.Apellidos, p.Email, p.Telefono, p.Dni, p.Direccion, p.Cp, p.Poblacion, p.Provincia, p.Pais, p.Observaciones
            FROM Inscritos i
            LEFT JOIN Personas p ON i.Id_persona = p.Id_persona
            WHERE i.Id_acto = :idActo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idActo' => $idActo]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addInscrito($idActo, $idPersona) {
        $inscritoModel = new Inscritos();
        return $inscritoModel->save($idActo, $idPersona);
    }

    public function removeInscrito($idInscripcion) {
        $inscrito = new Inscritos();
        return $inscrito->delete($idInscripcion);
    }

}