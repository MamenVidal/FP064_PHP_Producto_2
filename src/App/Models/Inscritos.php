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
    
}