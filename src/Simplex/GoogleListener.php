<?php

namespace Simplex;

class GoogleListener
{
    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        // Add the Google Analytics code only if the response is not a redirection,
        // if the requested format is HTML and if the response content type is HTML.
        if ($response->isRedirection()
            || ($response->headers->has('Content-Type') && !str_contains($response->headers->get('Content-Type'), 'html'))
            || 'html' !== $event->getRequest()->getRequestFormat()
        ) {
            return;
        }

        $response->setContent($response->getContent().'GA CODE');
    }
}