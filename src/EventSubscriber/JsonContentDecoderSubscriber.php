<?php

namespace App\EventSubscriber;

use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class JsonContentDecoderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['onKernelRequest', 251],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $content = $request->getContent();
        if (
            $content &&
            $request->getContentTypeFormat() == 'json' &&
            in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])
        ) {
            try {
                $data = json_decode($content, true, flags: JSON_THROW_ON_ERROR);
                $request->request = new ParameterBag($data);
            } catch (JsonException) {
                $event->setResponse(new Response('Bad json', status: 400));
            }
        }
    }
}