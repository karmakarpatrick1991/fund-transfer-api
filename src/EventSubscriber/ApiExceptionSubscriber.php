<?php
namespace App\EventSubscriber;

use App\Exception\AccountNotFoundException;
use App\Exception\InsufficientFundsException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onException'];
    }

    public function onException(ExceptionEvent $event)
    {

        $e = $event->getThrowable();

        $status = 500;
        $message = $e->getMessage();

        if ($e instanceof InsufficientFundsException) {
            $status = 422;
            $message = 'INSUFFICIENT_FUNDS';
        }

        if ($e instanceof AccountNotFoundException) {
            $status = 404;
            $message = 'ACCOUNT_NOT_FOUND';
        }

        $event->setResponse(new JsonResponse([
            'error' => $message
        ], $status));
    }
}
