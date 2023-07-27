<?php

declare(strict_types=1);

namespace Blog\Route;

use Blog\Database;
use PDOException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class AdminPage
{
    private Database $database;
    private Environment $view;
    
    public function __construct(Environment $view, Database $database)
    {
        $this->view = $view;
        $this->database = $database;
    }
    
    public function LoginPage(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->view->render('admin.twig');
        $response->getBody()->write($body);
        return $response;
    }
    
    public function AdminLogin(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $login = $data['login'];
        $password = $data['password'];
        if ($login === getenv('ADMPAGE_LOGIN') && $password === getenv('ADMPAGE_PASSWORD')) {
            $body = $this->view->render('download-page.twig');
            $response->getBody()->write($body);
        } else {
            echo 'Неправельный логин или пароль';
        }
        return $response;
    }
    
    public function AdminDownload(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $title = $data['title'];
        $url_key = $data['url_key'];
        $image_path = $data['image_path'];
        $description = $data['description'];
        $content = $data['content'];
        $repo_url = $data['repo_url'];
        $date = date('Y-m-d H:i:s');
        if($title != '' && $url_key != '' && $image_path != '' && $description != '' && $content != '') {
            try {   
                $query = $this->database->getConnection()->prepare(
                    "INSERT INTO post (title, url_key, image_path, description, content, repo_url, published_date) VALUES (:title, :url_key, :image_path, :description, :content, :repo_url, :date)"
                );
                $query->bindParam(':title', $title);
                $query->bindParam(':url_key', $url_key);
                $query->bindParam(':image_path', $image_path);
                $query->bindParam(':description', $description);
                $query->bindParam(':content', $content);
                $query->bindParam(':repo_url', $repo_url);
                $query->bindParam(':date', $date);
                $query->execute();
                $body = 'Пост успешно загружен';
                $response->getBody()->write($body);
            } catch (PDOException $e) {
                echo "Ошибка при загрузке данных: " . $e->getMessage();
            }
        } else {
            $body = 'Вы что-то не ввели, вернитесь назад';
            $response->getBody()->write($body);
        }
        return $response;
    }
}