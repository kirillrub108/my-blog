<?php

declare(strict_types=1);

namespace Blog\Route;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class AboutPage
{
    private Environment $view;
    
    public function __construct(Environment $view)
    {
        $this->view = $view;
    }
    
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->view->render('about.twig', [
            'name' => 'Kirill'
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}