<?php
/**
 * Klein (klein.php) - A fast & flexible router for PHP
 *
 * @author      Chris O'Hara <cohara87@gmail.com>
 * @author      Trevor Suarez (Rican7) (contributor and v2 refactorer)
 * @copyright   (c) Chris O'Hara
 * @link        https://github.com/chriso/klein.php
 * @license     MIT
 */

namespace Klein\Expression;

use Klein\Route;

/**
 * RouteCompilerInterface
 *
 * A compiler that turns routes into regular expressions
 */
interface RouteCompilerInterface
{

    /**
     * Compile a route into a regular expression
     *
     * @param Route $route  The input route to compile
     * @return string       The compiled regular expression
     */
    public function compile(Route $route);
}
