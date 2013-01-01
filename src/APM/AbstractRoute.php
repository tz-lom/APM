<?php

namespace APM;

interface AbstractRoute
{
    /**
     * Prepends prefix to path, prefix defined in subsection
     *
     * @param $prefix
     */
    public function addPrefix($prefix);

    /**
     * Compares path with stored template.
     * Returns result of comparation.
     *
     * @param $path Path to compare with
     * @return bool
     */
    public function checkPath($path);

    /**
     * Returns path that matches with stored template.
     * If necessary $params array must contain arguments to restore path.
     *
     * @param array $params
     * @return string
     */
    public function rebuildPath(array $params = array());
}
