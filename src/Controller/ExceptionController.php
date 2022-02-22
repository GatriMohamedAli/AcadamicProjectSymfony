<?php
namespace  App\Controller;


use Symfony\Component\HttpFoundation\Response;

class ExceptionController
{

    public function showException(): Response
    {
        $response = new Response();
        $response->setContent("Some random text");
        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->setStatusCode(200);
        return $response;
    }
}