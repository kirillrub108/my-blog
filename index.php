<?php

use Slim\Factory\AppFactory;
use Blog\Route\AboutPage;
use Blog\Route\BlogPage;
use Blog\Route\HomePage;
use Blog\Route\PostPage;
use Blog\Slim\TwigMiddleware;
use DevCoder\DotEnv;
use DI\ContainerBuilder;

require __DIR__ . '/vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions('config/di.php');
$absolutePathToEnvFile = __DIR__ . '/.env';
(new DotEnv($absolutePathToEnvFile))->load();

$container = $builder->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->add($container->get(TwigMiddleware::class));

$app->get('/', HomePage::class . ':execute');
$app->get('/about', AboutPage::class);
$app->get('/blog[/{page}]', BlogPage::class);
$app->get('/{url_key}', PostPage::class);

$app->run();