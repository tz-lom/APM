<?php

namespace APM;

class RouterSubSection implements RouterInterface
{
    /**
     * @var RouterInterface $router
     */
    protected $router;
    /**
     * @var string $prefix
     */
    protected $prefix;

    public function __construct(RouterInterface $router, $prefix)
    {
        $this->router = $router;
        $this->prefix = $prefix;
    }

    public function add(AbstractRoute $route)
    {
        $route->addPrefix($this->prefix);
        $this->router->add($route);
        return $this;
    }


    public function beginSubSection($prefix)
    {
        return new RouterSubSection($this, $prefix);
    }

    public function endSubSection()
    {
        return $this->router;
    }

    public function getRoot()
    {
        return $this->router->getRoot();
    }
}
