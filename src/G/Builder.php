<?php

namespace G;

class Builder
{
    private $container;

    function __construct(\Pimple $container)
    {
        $this->container = $container;
    }

    public function create($class, $input = [])
    {
        if (!isset($this->container[$class])) {
            $this->container[$class] = function () use ($class, $input) {
                return $this->getInstance($class, $input);
            };
        }

        return $this->container[$class];
    }

    public function call($controller, $input = [])
    {
        return call_user_func_array($controller, $this->getDependencies($controller, $input));
    }

    private function getInstance($class, $input)
    {
        $metaClass = new \ReflectionClass($class);

        return $metaClass->hasMethod('__construct') ?
            $metaClass->newInstanceArgs($this->getDependencies([$class, '__construct'], $input)) :
            new $class;
    }

    private function getDependencies($controller, $input)
    {
        $method       = new \ReflectionMethod($controller[0], $controller[1]);
        $dependencies = [];
        foreach ($method->getParameters() as $param) {
            $parameterName = $param->getName();
            if (isset($input[$parameterName])) {
                $dependencies[$parameterName] = $input[$parameterName];
            } else {
                if (isset($param->getClass()->name)) {
                    $dependencies[$parameterName] = $this->create($param->getClass()->name);
                } elseif (isset($this->container[$parameterName])) {
                    $dependencies[$parameterName] = $this->container[$parameterName];
                }
            }
        }

        return $dependencies;
    }
}