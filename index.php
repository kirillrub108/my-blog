<?php

use PSpell\Config;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use \Twig\Loader\FilesystemLoader;
use Blog\PostMapper;

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$view = new Environment($loader);

$config = include 'config/database.php';
$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];

try {$connection = new PDO($dsn, $username, $password);
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Database error:' . $e->getMessage();
    die();
}

$postMapper = new PostMapper($connection);

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) use ($view, $postMapper) {
    $posts = $postMapper->getList('DESC');
    
    $body = $view->render('index.twig', [
        'posts' => $posts
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function (Request $request, Response $response, $args) use ($view) {
    $body = $view->render('about.twig', [
        'name' => 'Kirill'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/{url_key}', function (Request $request, Response $response, $args) use ($view, $postMapper) {
    $post = $postMapper->getByUrlKey((string) $args['url_key']);
    if (empty($post)) {
        $body = $view->render('not-found.twig');
    } else {
        $body = $view->render('post.twig', [
            'post' => $post
        ]);
    }
    
    $response->getBody()->write($body);
    return $response;
});

$app->run();