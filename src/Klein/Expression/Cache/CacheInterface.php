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

namespace Klein\Expression\Cache;

/**
 * CacheInterface
 *
 * A cache for quickly saving and retrieving expressions
 */
interface CacheInterface
{

    /**
     * Fetch an expression from the cache
     *
     * @param string $key The key used to lookup the expression
     * @return string
     */
    public function fetch($key);

    /**
     * Store an expression in the cache
     *
     * @param string $key        The key of the stored location for later lookup
     * @param string $expression The expression to store
     * @return void
     */
    public function store($key, $expression);
}
