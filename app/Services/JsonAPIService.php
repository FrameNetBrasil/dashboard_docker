<?php

namespace App\Services;

use Orkester\Exception\ERuntimeException;
use Orkester\JsonApi\JsonApi;
use Orkester\Manager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class JsonAPIService //extends JsonApi
{
    public function handleService(Request $request, Response $response, array $args): array
    {
        ['service' => $service, 'action' => $action] = $args;
        $instance = static::getEndpointInstance($service);
        if (method_exists($instance, $action)) {
            try {
                $instance->setRequestResponse($request, $response);
                Manager::getData()->id = Manager::getData()->id ?? $args['id'] ?? null;
                $instance->init();
                $data = (array)Manager::getData();
                $class = $instance::class;
                $arguments = $this->buildArguments($data, $class . '::' . $action);
                $result = $instance->$action(...$arguments);
                $content = (object)['data' => $result];
                return [$content, 200];
            } catch (ERuntimeException $e) {
                $code = $e->getCode();
                $content = static::createErrorResponse(static::createError($code, 'App Error', $e->getMessage()));
                return [$content, 200];
            }
        } else {
            throw new \InvalidArgumentException('Service not found', 404);
        }
    }

}