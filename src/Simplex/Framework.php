<?php

namespace Simplex;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;

class Framework
{
    public function __construct(
        private UrlMatcher $matcher,
        private ControllerResolver $controllerResolver,
        private ArgumentResolver $argumentResolver
    ) {
    }

    public function handle(Request $request): Response
    {
        $this->matcher->getContext()->fromRequest($request);

        try {
            // Request attributes are extracted to keep our templates simple
            $attributes = $this->matcher->match($request->getPathInfo());
            $request->attributes->add($attributes);

            // the "_controller" request attribute contains the controller associated with the Request
            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            return call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $exception) {
            return new Response('Not Found', 404);
        } catch (Exception $exception) {
            // 500 errors are now managed correctly
            return new Response('An error occurred', 500);
        }
    }
}