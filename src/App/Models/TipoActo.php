<?php

namespace App\Models;

class TipoActo extends \Core\Model
{
    public $table = 'Tipo_acto';
    public $pkField = 'Id_tipo_acto';

    protected function defineTable(): string {
        return $this->table;
    }

    protected function definePkField(): string {
        return $this->pkField;
    }
    
}