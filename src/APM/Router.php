<?php

namespace APM;

class Router implements RouterInterface
{
    /**
     * @var AbstractRoute[] $routes
     */
    protected $routes = array();
	
	public function add(AbstractRoute $route)
	{
		$this->routes[] = $route;
        return $this;
	}
	
	public function beginSubSection($prefix)
	{
		return new RouterSubSection($this, $prefix);
	}
	
	public function endSubSection()
	{
		return $this;
	}

    /**
     * @param string $path
     *
     * @return AbstractRoute|NULL
     */
    public function findFirstRoute($path)
	{
		foreach($this->routes as $route)
		{
			if($route->checkPath($path))
			{
				return $route;
			}
		}
        return NULL;
	}

    /**
     * @param string $path
     *
     * @return AbstractRoute[]
     */
    public function findAllRoutes($path)
    {
        $result = array();
        foreach($this->routes as $route)
        {
            if($route->checkPath($path))
            {
                $result[] = $route;
            }
        }
        return $result;
    }

    public function getRoot()
    {
        return $this;
    }
}
