<?php

declare(strict_types=1);

namespace Blog;

use PDO;

class Database
{
    private PDO $connection;
    
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}