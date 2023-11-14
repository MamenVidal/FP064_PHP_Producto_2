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
    
}