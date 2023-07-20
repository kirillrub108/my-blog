<?php

declare(strict_types=1);

namespace Blog\Route;

use Blog\LatestPost;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;

class HomePage
{
    private LatestPost $latestsPosts;
    private Environment $view;
    
    public function __construct(LatestPost $latestsPosts, Environment $view)
    {
        $this->latestsPosts = $latestsPosts;
        $this->view = $view;
    }
    
    public function execute(Request $request, Response $response): Response
    {
        $posts = $this->latestsPosts->get(3);
        
        $body = $this->view->render('index.twig', [
            'posts' => $posts
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}