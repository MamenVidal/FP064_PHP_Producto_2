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
    
}