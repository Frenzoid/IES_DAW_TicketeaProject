<?php

namespace liveticket\core\database;

use PDO;
use Exception;

class QueryBuilder
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * QueryBuilder constructor.
     * @param PDO $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function findAll(string $table, string $classEntity) : array
    {
        $sql = "SELECT * FROM $table";
        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute();

        if ($res === FALSE)
            throw new Exception('No se ha podido ejecutar la query ');

        return $pdoStatement->fetchAll(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            "liveticket\\app\\entities\\$classEntity");
    }

    public function findBy(string $table, string $classEntity, array $filters, $withLike = false, $andOr = false) : array
    {
        $sql = "SELECT * FROM $table";
        if (count($filters)>0)
        {
            if ($withLike === true)
            {
                $filters = array_map(function ($valor)
                {
                    return '%' . $valor . '%';
                }, $filters);
            }

            if(!$andOr)
                $sql .= ' WHERE ' . $this->getFilters($filters, '', $withLike);
            else
                $sql .= ' WHERE ' . $this->getFiltersOr($filters, '', $withLike);

        }

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($filters);

        if ($res === FALSE)
            throw new Exception('No se ha podido ejecutar la query ');

        return $pdoStatement->fetchAll(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            "liveticket\\app\\entities\\$classEntity");
    }

    public function findOneBy(string $table, string $classEntity, array $filters, $withLike = false,  $andOr = false)
    {
        $result = $this->findBy($table, $classEntity, $filters, $withLike, $andOr);

        if (count($result) > 0)
            return $result[0];

        return null;
    }

    public function find(string $table, string $classEntity, $id)
    {
        $sql = "SELECT * FROM $table WHERE id=:id";
        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute([
            ':id' => $id
        ]);

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query ');

        $pdoStatement->setFetchMode(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            "liveticket\\app\\entities\\$classEntity");

        return $pdoStatement->fetch();
    }

    public function insert(string $table, array $parameters)
    {
        $keys = array_keys($parameters);

        $sql = sprintf(
            "INSERT INTO $table (%s) VALUES (%s)",
            implode(', ', $keys),
            ':' . implode(', :', $keys));

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($parameters);

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query de inserción');
    }

    private function getParameters(array $parameters)
    {
        $parametersConcatenados = [];

        foreach($parameters as $nombre=>$valor)
            $parametersConcatenados[] = $nombre . '=:P' . $nombre;

        return implode(', ', $parametersConcatenados);
    }

    private function getFilters(array $filters, string $letra='', bool $withLike=false)
    {
        $filtersConcatenados = [];

        foreach($filters as $nombre=>$valor)
        {
            if ($withLike === false)
                $filtersConcatenados[] = $nombre . '=:'. $letra . $nombre;
            else
                $filtersConcatenados[] = $nombre . ' like :'. $letra . $nombre;
        }

        return implode(' and ', $filtersConcatenados);
    }

    private function getParametersExecute(array $parameters, array $filters)
    {
        $parametersExecute = [];

        foreach($parameters as $key=>$value)
            $parametersExecute['P'.$key] = $value;

        foreach($filters as $key=>$value)
            $parametersExecute['F'.$key] = $value;

        return $parametersExecute;
    }

    public function update(string $table, array $parameters, array $filters)
    {
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $table,
            $this->getParameters($parameters),
            $this->getFilters($filters, 'F'));

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute(
            $this->getParametersExecute($parameters, $filters)
        );

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query de inserción');
    }

    public function delete(string $table, array $filters)
    {
        $sql = sprintf(
            "DELETE FROM %s WHERE %s",
            $table,
            $this->getFilters($filters));

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute($filters);

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query de eliminación');
    }

    // QUERYS PARA liveticket

    private function getFiltersOr(array $filters, string $letra='', bool $withLike=false)
    {
        $filtersConcatenados = [];

        foreach($filters as $nombre=>$valor)
        {
            if ($withLike === false)
                $filtersConcatenados[] = $nombre . '=:'. $letra . $nombre;
            else
                $filtersConcatenados[] = $nombre . ' like :'. $letra . $nombre;
        }

        return implode(' or ', $filtersConcatenados);
    }

    public function compareDates(string $table, string $classEntity, array $tableDates,  string $date='0') : array
    {
        $queryParameters = [];
        if($date == '0')
            $date = date('Y-m-d H:i:s');

        $sql = "SELECT * FROM $table WHERE";

        foreach ($tableDates as $key => $value){
            $queryParameters[] = "(" . $key . " " . $value . " " . ':date' . ")";
        }

        $sql = $sql . ' ' . implode( ' AND ', $queryParameters);

        $pdoStatement = $this->pdo->prepare($sql);
        $res = $pdoStatement->execute([':date' => $date]);

        if ($res === false)
            throw new Exception('No se ha podido ejecutar la query ');

        return $pdoStatement->fetchAll(
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            "liveticket\\app\\entities\\$classEntity");
    }
}