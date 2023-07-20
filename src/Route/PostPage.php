<?php

declare(strict_types=1);

namespace Blog\Route;

use Blog\PostMapper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class PostPage
{
    private Environment $view;

    private PostMapper $postMapper;
    
    public function __construct(Environment $view, PostMapper $postMapper)
    {
        $this->view = $view;
        $this->postMapper = $postMapper;
    }
    
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = []): ResponseInterface
    {
        $post = $this->postMapper->getByUrlKey((string) $args['url_key']);
        if (empty($post)) {
            $body = $this->view->render('not-found.twig');
        } else {
            $body = $this->view->render('post.twig', [
                'post' => $post
            ]);
        }
        
        $response->getBody()->write($body);
        return $response;
    }
}