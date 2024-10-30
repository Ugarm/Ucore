<?php

declare(strict_types=1);


namespace Ubase\Router;

use Exception;
class Router implements RouterInterface
{
    /**
     * returns an array of route from our routing table
     * @var array
     */
    protected array $routes = [];
    /**
     * returns an array of route params
     * @var array
     */
    protected array $parameters = [];
    /**
     * Adds a marker to the controller name so that it's detected as such
     * @var string
     */
    protected string $controllerMarker = 'controller';

    /**
     * @inheritDoc
     */
    public function add(string $route, array $parameters = []): void
    {
        $this->routes[$route] = $parameters;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function dispatch(string $url): void
    {
        if ($this->match($url)) {
            $controllerString = $this->parameters['controller'];
            $controllerString = $this->upperCamelCase($controllerString);
            $controllerString = $this->getNamespace($controllerString);

            if (class_exists($controllerString)) {
                $controllerObject = new $controllerString;
                $action = $this->parameters['action'];
                $action = $this->transformCamelCase($action);

                if (is_callable($controllerObject, $action)) {
                    $controllerObject->$action();
                } else {
                    throw new Exception;
                }
            }
            else {
                throw new Exception;
            }
        }
        else {
            throw new Exception;
        }
    }

    public function transformCamelCase(string $string) : string
    {
        return \lcfirst($this->upperCamelCase($string));
    }
    /**
     * Transforms strings from the controller request into upper camel case
     * @param string $string
     * @return string
     */
    public function upperCamelCase(string $string) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Get the namespace for a controller class.
     * Namespace defined within the route parameters.
     * @param string $string
     * @return string
     */
    public function getNamespace(string $string): string
    {
        $namespace = 'App\Controller\\';
        if (array_key_exists('namespace', $this->parameters)) {
            $namespace .= $this->parameters['namespace'] . '\\';
        }

        return $namespace;
    }

    /**
     * Check if routes correspond to the routing table, then sets $this->parameters
     * if a valid route is found
     *
     * @param string $url
     * @return boolean
     */
    private function match(string $url) : bool
    {
        foreach($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $params[$key] = $param;
                    }
                }
                $this->parameters = $params;

                return true;
            }
        }
        return false;
    }
}