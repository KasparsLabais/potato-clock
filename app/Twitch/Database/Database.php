<?php

namespace App\Twitch\Database;

use App\Twitch\Helpers\Config;
use mysqli;

class Database
{

    private static $columnTypesWithAllowedParam = array('int', 'varchar');

    use Config;

    private static $connection;
    public string $name = 'jojo';

    public function __construct() 
    {
        self::connect();
    }

    public static function connect() : void
    {

        $host = self::config('database', 'host');
        $user = self::config('database', 'user');
        $password = self::config('database', 'password');
        $database = self::config('database', 'database');

        $mysqlCon = mysqli_connect($host, $user, $password, $database);

        if (!$mysqlCon) {
            die("Connection failed: " . mysqli_connect_error());
        }

        self::$connection = $mysqlCon;
    }

    public function __get($name) {
        var_dump($name);die();
    }

    public static function getConnection() : mysqli 
    {
        return self::$connection;
    }


    // All the db logic goes here
    public function hasMigrationTable(): bool  
    {
        $qr = "select * from information_schema.tables where `TABLE_SCHEMA` = 'twitch' and `TABLE_NAME` = 'migrations'";
        $result = mysqli_query(self::getConnection(), $qr);

        $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
   
        return is_null($data) ? false : true;
    }

    public static function runMigration($className, $fileName): void 
    {
        require __DIR__ . "/../../../database/{$fileName}.php";
        $className::migrate();
    }

    public static function createTable(string $tableName, array $tableFields)
    {
    
        //var_dump($tableFields);
        $fields = implode(", ", array_map([__CLASS__, 'parseColumn'], $tableFields));
        $primaryKey = '';

        foreach ($tableFields as $field) {
            if (isset($field['autoIncrement']) && $field['autoIncrement'] === true) {
                $primaryKey = $field['fieldName'];
                break;
            }
        }


        $sql = 'CREATE TABLE IF NOT EXISTS `' . $tableName . '` (' . $fields . ', PRIMARY KEY (`' . $primaryKey . '`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
        $result = mysqli_query(self::getConnection(), $sql);

        var_dump($result); die();
    }

    public static function parseColumn($columnData) 
    {
        //var_dump($columnData);die();
        $columnString = "`{$columnData['fieldName']}` {$columnData['type']}";

        if (in_array($columnData['type'], self::$columnTypesWithAllowedParam)) {
            $columnString .= "({$columnData['fieldParam']})";
        }

        //rest of the additions to the field AUTO, DEFAULT, NULLABLE, ETC
        if (!empty($columnData['autoIncrement'])) {
            $columnString .= " AUTO_INCREMENT";
        }

        if (!empty($columnData["nullable"])) {
            $columnString .= " NULL";
        } else {
            $columnString .= " NOT NULL";
        }

        if (!empty($columnData["default"])) {
            $columnString .= " DEFAULT {$columnData['default']}";
        }

        return $columnString;

    }

}