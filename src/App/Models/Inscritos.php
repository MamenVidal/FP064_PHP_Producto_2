<?php

namespace App\Models;

class Inscritos extends \Core\Model
{
    protected $table = 'Inscritos';
    protected $pkField = 'Id_inscripcion';

    protected function defineTable(): string {
        return $this->table;
    }

    protected function definePkField(): string {
        return $this->pkField;
    }

    public function existeInscripcion($idActo, $idPersona) {
        $sql = "SELECT * FROM {$this->table} WHERE Id_acto = :idActo AND Id_persona = :idPersona";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idActo' => $idActo, 'idPersona' => $idPersona]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function save($idActo, $idPersona) {
        if ($this->existeInscripcion($idActo, $idPersona)) {
            return false;
        }
        $sql = "INSERT INTO {$this->table} (Id_acto, Id_persona, Fecha_inscripcion) VALUES (:idActo, :idPersona, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['idActo' => $idActo, 'idPersona' => $idPersona]);
    }

    public function getNumInscritos($idActo) {
        $sql = "SELECT COUNT(*) as numInscritos FROM {$this->table} WHERE Id_acto = :idActo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idActo' => $idActo]);
        $inscritos = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $inscritos['numInscritos'];
    }

    public function getInscritos($idActo) {
        if(!is_numeric($idActo)) {
            $sql = "SELECT I.*, P.Nombre, P.Apellido1, P.Apellido2, A.Titulo
                FROM {$this->table} I 
                LEFT JOIN Personas P ON P.Id_persona = I.Id_persona 
                LEFT JOIN Actos A ON A.Id_acto = I.Id_acto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        $sql = "SELECT I.*, P.Nombre, P.Apellido1, P.Apellido2, A.Titulo
            FROM {$this->table} I 
            LEFT JOIN Personas P ON P.Id_persona = I.Id_persona 
            LEFT JOIN Actos A ON A.Id_acto = I.Id_acto 
            WHERE I.Id_acto = :idActo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idActo' => $idActo]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}