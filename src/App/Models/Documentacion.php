<?php

namespace App\Models;

class Documentacion extends \Core\Model
{
    protected $table = 'Documentacion';
    protected $pkField = 'Id_presentacion';

    protected function defineTable(): string {
        return $this->table;
    }

    protected function definePkField(): string {
        return $this->pkField;
    }
    
}