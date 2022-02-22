<?php
// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\ControllerDoesNotReturnResponseException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
{
// You get the exception object from the received event
        if ($event->getThrowable() instanceof ControllerDoesNotReturnResponseException) {
            $exception = $event->getThrowable();
            $message = sprintf(
                'My Error says: %s with code: %s',
                $exception->getMessage(),
                $exception->getCode()
            );

// Customize your response object to display the exception details
            $response = new Response();
            $response->setContent($message);

// HttpExceptionInterface is a special type of exception that
// holds status code and header details
            if ($exception instanceof ControllerDoesNotReturnResponseException) {
                $response->setContent("YOUR ARE NOT LOGGED IN OR YOU ARE NOT AUTHORIZED TO ACCESS THIS PAGE");


            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

// sends the modified response object to the event
//        $this->twig->render('Errorpage.html.twig');
            $event->setResponse($response);
        }
}
}