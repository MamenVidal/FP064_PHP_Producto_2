<?php

namespace App\Models;

class Personas extends \Core\Model
{
    public $table = 'Personas';
    public $pkField = 'Id_persona';

    protected function defineTable(): string {
        return $this->table;
    }

    protected function definePkField(): string {
        return $this->pkField;
    }
    
}