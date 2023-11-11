<?php

namespace App\Models;

class TiposUsuarios extends \Core\Model
{
    const DEFAULT_TIPO_USUARIO = 3; // usuario

    protected $table = 'Tipos_usuarios';
    protected $pkField = 'Id_tipo_usuario';

    protected function defineTable(): string {
        return $this->table;
    }

    protected function definePkField(): string {
        return $this->pkField;
    }
    
}