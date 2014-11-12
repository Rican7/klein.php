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

namespace Klein\Matcher;

use Klein\Expression\Cache\CacheInterface;
use Klein\Expression\RouteCompiler;
use Klein\Expression\RouteCompilerInterface;
use Klein\Request;
use Klein\Route;

/**
 * Matcher
 *
 * The default matcher for Klein
 */
class Matcher
{

    /**
     * Properties
     */

    /**
     * The compiler that turns routes into regular expressions
     *
     * @type RouteCompilerInterface
     */
    protected $route_compiler;

    /**
     * The cache for quickly saving and retrieving expressions
     *
     * @type CacheInterface
     */
    protected $expression_cache;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param RouteCompilerInterface $route_compiler
     * @param CacheInterface $expression_cache
     */
    public function __construct(RouteCompilerInterface $route_compiler = null, CacheInterface $expression_cache = null)
    {
        $this->route_compiler = $route_compiler ?: new RouteCompiler();
        $this->expression_cache = $expression_cache;
    }

    /**
     * Check if a request's method matches a route's defined method(s) or not
     *
     * @param Route $route
     * @param Request $request
     * @return boolean
     */
    protected function isMethodMatch(Request $request, Route $route)
    {
        $method_match = false;

        // Cache some route details so we don't have to make a function call each time
        $method = $route->getMethod();

        // If the route method is null, we'll consider it a catch-all
        if (null === $method) {
            $method_match = true;

        } else {
            foreach ((array) $method as $test) {
                if ($request->method($test)) {
                    $method_match = true;
                    break;

                } elseif ($request->method('HEAD')
                    && (strcasecmp($test, 'HEAD') === 0 || strcasecmp($test, 'GET') === 0)) {
                    // Test for HEAD request (like GET)

                    $method_match = true;
                    break;
                }
            }
        }

        return $method_match;
    }

    /**
     * Compile a route into a regular expression
     *
     * This will attempt to use our expression cache if available,
     * otherwise it'll just compile with our route compiler
     *
     * @param Route $route
     * @return string
     */
    protected function compile(Route $route)
    {
        $expression = null;

        if (null !== $this->expression_cache) {
            $expression = $this->expression_cache->fetch($route->getPath());
        } else {
            $expression = $this->route_compiler->compile($route);
        }

        if (null !== $this->expression_cache) {
            $this->expression_cache->store($route->getPath(), $expression);
        }

        return $expression;
    }

    /**
     * Check if a request matches a defined route
     *
     * @param Request $request
     * @param Route $route
     * @return MatchResult
     */
    public function match(Request $request, Route $route)
    {
        $match = false;
        $negate = false;

        $params = array();
        $path = $route->getPath();

        // TODO: Should be replaced with an "isNegated" call in the future
        if (isset($path[0]) && $path[0] === '!') {
            $negate = true;
            $i = 1; // TODO: Remove
        } else {
            $i = 0; // TODO: Remove
        }

        // TODO: Should check if the route path is a "wildcard" in the future
        if (null === $path || $path === '*') {
            $match = true;

        } else {
            // TODO: Should be replaced with a check for a custom regex in the future
            if (isset($path[$i]) && $path[$i] === '@') {
                $regex = '`'. substr($path, $i + 1) .'`';

            } else {
                // TODO: Try naively matching before compiling
                $regex = $this->compile($route);
            }

            $match = preg_match($regex, $request->pathname(), $params);
        }

        return new MatchResult(
            $match ^ $negate,
            $this->isMethodMatch($request, $route),
            $params
        );
    }
}
