<?php
// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ControllerDoesNotReturnResponseException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
{
        $response = new Response();
//        if ($event->getThrowable() instanceof ControllerDoesNotReturnResponseException) {
//            $exception = $event->getThrowable();
//            $message = sprintf(
//                'My Error says: %s with code: %s',
//                $exception->getMessage(),
//                $exception->getCode()
//            );
//            $response->setContent($message);
//            $response->setContent("YOUR ARE NOT LOGGED IN OR YOU ARE NOT AUTHORIZED TO ACCESS THIS PAGE");
//            $event->setResponse($response);
//
//        }
//        else if($event->getThrowable() instanceof AccessDeniedHttpException){
//            $response->setContent("YOU ARE NOT AUTHORIZED TO ACCESS THIS PAGE, SORRY");
//            $event->setResponse($response);
//            }

            return $event->getThrowable();

}
}