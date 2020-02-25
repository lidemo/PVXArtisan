<?php

namespace PVXArtisan\Helpers;

class Database{

    protected $pdo;

    function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function insertHeaders($pvxObject, String $tableName) { //polymorphism needed
        $headers = CsvToArrayConverter::csvToArray(explode("\n", $pvxObject->get())[0]);

        $sql = "CREATE TABLE $tableName( id INT AUTO_INCREMENT, ";

        foreach ($headers as $header) {
            $header = preg_replace('(")', '', $header);
            $header = preg_replace('( )', '_', $header);
            $sql .= "$header VARCHAR(200),";
        }
        
        $sql .= "PRIMARY KEY (id) );";
        
        $this->pdo->exec($sql);
    }

}