<?php

namespace Core;

class Router {

    public static function start() {

        $request = new \Core\Request();
        $response = new \Core\JsonResponse();

        if (!self::isUrlValid($request))
            $response->sendError(400, \Core\JsonResponse::URL_VALIDATION_ERROR);

        $controllerName = ucfirst($request->resourceName) . 'Controller';
        $controllerName = '\Controllers\\'.$controllerName;

        $modelName = ucfirst($request->resourceName) . 'Model';
        $modelName = '\Models\\'.$modelName;

        if ( class_exists($controllerName) && class_exists($modelName) ) {
            $model = new $modelName(new \Core\Database());
            $controller = new $controllerName($request, $response, $model);
            $action = strtolower($request->method) . 'Action';

            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                $response->send(405);
            }
        } else {
            $response->send(404);
        }

    }

    protected static function isUrlValid($request) {
        // Match http://{resource}/{id}/ pattern
        // Match http://{resource}/ pattern
        return empty($request->urlParts[3]);
    }
}