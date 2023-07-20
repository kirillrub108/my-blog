<?php

declare(strict_types=1);

namespace Blog\Route;

use Blog\PostMapper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class BlogPage
{
    private Environment $view;

    private PostMapper $postMapper;
    
    public function __construct(Environment $view, PostMapper $postMapper)
    {
        $this->view = $view;
        $this->postMapper = $postMapper;
    }
    
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $page = isset($args['page']) ? (int) $args['page'] : 1;
        $limit = 2;
        
        $posts = $this->postMapper->getList($page, $limit, 'DESC');
        
        $totalCount = $this->postMapper->getTotalCount();
        $body = $this->view->render('blog.twig', [
            'posts' => $posts,
            'pagination' => [
                'current' => $page,
                'paging' => ceil($totalCount / $limit)
            ]
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}