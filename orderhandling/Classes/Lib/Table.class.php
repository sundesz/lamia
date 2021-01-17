<?php

namespace orderhandling\Classes\Lib;

use orderhandling\Classes\Connection\Dbh;

abstract class Table extends Dbh
{
    protected $conn;
    protected $table;

    public function __construct()
    {
    }

    /**
     * Basic select statement
     * @return string
     */
    protected function selectSql()
    {
        return "SELECT * FROM {$this->table}";
    }

    /**
     * Get all record
     * @return mixed
     */
    public function getAll()
    {
        $sql = $this->selectSql();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * Get one record by id
     * @param int $id
     * @return mixed
     */
    public function findOneById(int $id)
    {
        $sql = $this->selectSql() . " WHERE {$this->table}_id = :id;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}