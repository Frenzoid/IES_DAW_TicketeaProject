<?php

namespace liveticket\core\database;

use PDO;
use PDOException;

class Connection
{
    public static function make(array $database)
    {
        try
        {
            $pdo = new PDO(
                $database['connection'] . ';dbname=' . $database['name'],
                $database['username'],
                $database['password'],
                $database['options']);
        }
        catch(PDOException $pdoException)
        {
            die ($pdoException->getMessage());
        }

        return $pdo;
    }
}