<?php

namespace App\Core;

use PDO;

class Database
{
    private string $db_host = DB_HOST;
    private string $db_name = DB_NAME;
    private string $db_user = DB_USER;
    private string $db_pass = DB_PASS;

    protected $db;
    protected $stmt;

    public function __construct()
    {
        $dsn = "mysql:host=$this->db_host;dbname=$this->db_name;charset=utf8mb4";

        $this->db = new PDO($dsn, $this->db_user, $this->db_pass);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    public function query($sql)
    {
        $this->stmt = $this->db->prepare($sql);

        return $this;
    }

    public function execute($params = [])
    {
        $this->stmt->execute($params);
    }

    public function get($params = [], $fetch_type = PDO::FETCH_OBJ)
    {
        $this->stmt->execute($params);

        return $this->stmt->fetchAll($fetch_type);
    }

    public function getFetch($params = [], $fetch_type = PDO::FETCH_OBJ)
    {
        $this->stmt->execute($params);
        return $this->stmt->fetch($fetch_type);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}
