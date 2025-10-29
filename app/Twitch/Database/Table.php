<?php

namespace App\Twitch\Database;

class Table 
{

    private array $fields = [];

    public function __construct(private string $tableName = '') {}

    public function primary(string $fieldName) : void 
    {
        $this->fields[] = [
            'type' => 'int',
            'fieldParam' => 11,
            'autoIncrement' => true,
            'fieldName' => $fieldName
        ];
    }

    public function string(string $fieldName, ?int $length = 255):void 
    {
        $this->fields[] = [
            'type' => 'varchar',
            'fieldParam' => $length,
            'fieldName' => $fieldName
        ];
    }

    public function dateTime(string $fieldName) : void 
    {
        $this->fields[] = [
            'type' => 'datetime',
            'fieldName' => $fieldName
        ];
    }

    public function tinyInt(string $fieldName) : void 
    {
        $this->fields[] = [
            'type' => 'tinyint',
            'fieldParam' => 8,
            'fieldName' => $fieldName
        ];
    }

    public function nullable() : void 
    {
        $this->fields[count($this->fields) - 1]['nullable'] = true;
    }
    
    public function default(int|string $value) {
        $this->fields[count($this->fields) -1]['default'] = $value;
    }


    public function save() : void 
    {
        Database::createTable($this->tableName, $this->fields);
    }


}