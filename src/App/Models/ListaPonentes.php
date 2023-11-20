<?php

namespace App\Models;

class ListaPonentes extends \Core\Model
{
    protected $table = 'Lista_Ponentes';
    protected $pkField = 'id_ponente';

    protected function defineTable(): string {
        return $this->table;
    }

    protected function definePkField(): string {
        return $this->pkField;
    }

    public function getPonentes($idActo) {
        if(!is_numeric($idActo)) {
            $sql = "SELECT P.*, PE.Nombre, PE.Apellido1, PE.Apellido2, A.Titulo
                FROM $this->table P 
                LEFT JOIN Personas PE ON PE.Id_persona = P.Id_persona 
                LEFT JOIN Actos A ON A.Id_acto = P.Id_acto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        $sql = "SELECT P.*, PE.Nombre, PE.Apellido1, PE.Apellido2, A.Titulo
            FROM $this->table P 
            LEFT JOIN Personas PE ON PE.Id_persona = P.Id_persona 
            LEFT JOIN Actos A ON A.Id_acto = P.Id_acto 
            WHERE P.Id_acto = :idActo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idActo' => $idActo]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}