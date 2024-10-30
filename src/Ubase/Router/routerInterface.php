<?php

declare(strict_types=1);

namespace Ubase\Router;
interface RouterInterface
{

    /**
     * Adds a route to the routing table
     *
     * @param string $route
     * @param array $parameters
     * @return void
     */
    public function add(string $route, array $parameters): void;

    /**
     * Dispatches route and creates controller objects then execute the default method
     * on that controller object
     *
     * @param string $url
     * @return void
     */
    public function dispatch(string $url): void;

}