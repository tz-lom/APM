<?php

namespace APM;

class URLRoute implements AbstractRoute
{
    protected $path;
    protected $pathRegexp = NULL;
    protected $pathPrintf = NULL;
    /**
     * @var \string[]
     */
    protected $slugs = array();

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function addPrefix($prefix)
    {
        $this->path = $prefix.$this->path;
        $this->dropCache();
    }

    protected function checkInputParameters()
    {
        return true;
    }

    /**
     * @return \string[]
     */
    public function getSlugs()
    {
        return $this->slugs;
    }

    /**
     * @param array $params optional
     *
     * @return \string
     * @throws CannotRebuildPath
     */
    public function rebuildPath(array $params = array())
    {
        $this->compilePath();
        $slugs = $this->slugs;
        foreach($slugs as $name=>$slug)
        {
            if(!array_key_exists($name, $params)) throw new CannotRebuildPath("Parameter $name must be provided");
            $slugs[$name] = $params[$name];
        }
        return vsprintf($this->pathPrintf, $slugs);
    }

    public function checkPath($path)
    {
        $this->compilePath();

        if(preg_match($this->pathRegexp, $path, $matches))
        {
            foreach($this->slugs as $name=>$value)
            {
                $this->slugs[$name] = isset($matches[$name])?$matches[$name]:NULL;
            }
            return $this->checkInputParameters();
        }
        return false;
    }

    protected function compilePath()
    {
        if($this->pathRegexp !== NULL && $this->pathPrintf !== NULL) return;

        $this->slugs = array();
        $this->pathRegexp = '';
        $this->pathPrintf = '';

        // replace slugs to placeholders
        $this->pathPrintf = preg_replace('@{(\w+)\??:?(.*?)}@u', '%s', str_replace('%','%%',$this->path));

        // extract slugs if any
        if(preg_match_all('@{(\w+\??):?(.*?)}@u', $this->path, $matches))
        {
            $slugs = array_combine($matches[1], $matches[2]);
            foreach($slugs as $name=>$regexp)
            {
                if($regexp=='') $regexp='[^\/]+';
                if(substr($name,-1)=='?')
                {
                    $name = substr($name,0,-1);
                    $this->slugs[$name] = "(?P<$name>$regexp)?";
                }
                else
                {
                    $this->slugs[$name] = "(?P<$name>$regexp)";
                }
            }
        }

        $path = str_replace('@','\\@',preg_quote($this->pathPrintf));

        $this->pathRegexp = '@^'.vsprintf($path, $this->slugs).'$@u';
    }

    protected function dropCache()
    {
        $this->pathRegexp = NULL;
        $this->pathPrintf = NULL;
    }
}
