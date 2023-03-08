<?php

namespace Simplex;

class ContentLengthListener
{
    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $headers = $response->headers;

        if (!$headers->has('Content-length') && !$headers->has('Transfert-Encoding')) {
            $headers->set('Content-length', \strlen($response->getContent()));
        }
    }
}