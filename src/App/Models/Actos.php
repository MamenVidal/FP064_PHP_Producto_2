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

}