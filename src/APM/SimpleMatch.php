<?php

namespace APM;

class SimpleMatch implements AbstractRoute
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function addPrefix($prefix)
    {
        $this->path = $prefix.$this->path;
    }

    public function checkPath($path)
    {
        return $this->path == $path;
    }

    public function rebuildPath(array $params = array())
    {
        return $this->path;
    }
}
