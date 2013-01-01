<?php
namespace APM;

interface RouterInterface
{
    /**
     * @param AbstractRoute $route
     *
     * @return RouterInterface
     */
    public function add(AbstractRoute $route);

    /**
     * @param $prefix
     *
     * @return RouterInterface
     */
    public function beginSubSection($prefix);

    /**
     * @return RouterInterface
     */
    public function endSubSection();

    /**
     * @return Router
     */
    public function getRoot();
}
