<?php

declare(strict_types=1);

namespace Blog;

use Exception;
use PDO;

class PostMapper 
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getByUrlKey(string $urlKey): ?array
    {
       $statement = $this->getConnection()->prepare('SELECT * FROM post WHERE url_key = :url_key');
       $statement->execute([
        'url_key' => $urlKey
       ]);

       $result = $statement->fetchAll();

       return array_shift($result);
    }

    public function getList(int $page = 1, int $limit = 2, string $direction = 'ASC'): ?array 
    {
        if (!in_array($direction, ['DESC', 'ASC'])) {
            throw new Exception('The direction is not supported.');
        }
        
        $start = ($page - 1) * $limit;
        $statement = $this->getConnection()->prepare('SELECT * FROM post ORDER BY published_date ' . $direction . 
        ' LIMIT ' . $start . ',' . $limit
    );

        $statement->execute();
        
        return $statement->fetchAll();

    }

    public function getTotalCount(): int
    {
        $statement = $this->getConnection()->prepare('SELECT count(post_id) as total FROM post');
        $statement->execute();

        return (int) ($statement->fetchColumn() ?? 0);
    }

    private function getConnection() : PDO 
    {
        return $this->database->getConnection();    
    }
}