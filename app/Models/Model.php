<?php

namespace App\Models;

use App\Core\Database;

class Model extends Database
{
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function all()
    {
        return $this->query("SELECT * FROM $this->table")->get();
    }

    public function find(int $id)
    {
        return $this->query("SELECT * FROM $this->table WHERE {$this->primaryKey} = :id")->getFetch(['id' => $id]);
    }

    public function destroy(int $id): int
    {
        $this->query("DELETE FROM $this->table WHERE {$this->primaryKey} = :id")->execute(['id' => $id]);

        return $this->rowCount();
    }
}
