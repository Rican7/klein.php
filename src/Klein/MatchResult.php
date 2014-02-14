<?php
/**
 * Klein (klein.php) - A lightning fast router for PHP
 *
 * @author      Chris O'Hara <cohara87@gmail.com>
 * @author      Trevor Suarez (Rican7) (contributor and v2 refactorer)
 * @copyright   (c) Chris O'Hara
 * @link        https://github.com/chriso/klein.php
 * @license     MIT
 */

namespace Klein;

use Klein\DataCollection\RouteCollection;

/**
 * MatchResult
 *
 * A value object class designed to represent the
 * result of the Klein matching algorithm
 *
 * @package     Klein
 */
class MatchResult
{

    /**
     * Properties
     */

    /**
     * RouteCollection of matched routes
     *
     * @var RouteCollection
     * @access protected
     */
    protected $matched;

    /**
     * An array of HTTP methods that were matched to the request
     *
     * @var array
     * @access protected
     */
    protected $methods_matched;

    /**
     * The contents buffered from our output buffer
     *
     * @var string
     * @access protected
     */
    protected $buffered_content;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param RouteCollection $matched
     * @param array $methods_matched
     * @param string $buffered_content
     * @access public
     */
    public function __construct(
        RouteCollection $matched,
        array $methods_matched = array(),
        $buffered_content = null
    ) {
        // Initialize the properties of our value object
        $this->setMatched($matched);
        $this->setMethodsMatched($methods_matched);
        $this->setBufferedContent($buffered_content);
    }

    /**
     * Get the matched routes
     *
     * @access public
     * @return RouteCollection
     */
    public function getMatched()
    {
        return $this->matched;
    }

    /**
     * Set the matched routes
     *
     * @param RouteCollection $matched
     * @access public
     * @return MatchResult
     */
    public function setMatched(RouteCollection $matched)
    {
        $this->matched = $matched;

        return $this;
    }

    /**
     * Get the matched HTTP methods
     *
     * @access public
     * @return array
     */
    public function getMethodsMatched()
    {
        return $this->methods_matched;
    }

    /**
     * Set the matched HTTP methods
     *
     * @param array $methods_matched
     * @access public
     * @return MatchResult
     */
    public function setMethodsMatched(array $methods_matched)
    {
        // Filter any null methods and make sure there are no duplicates
        $this->methods_matched = array_unique(
            array_filter($methods_matched)
        );

        return $this;
    }

    /**
     * Merge an array of matched HTTP methods into our current set
     *
     * @param array $methods_matched
     * @access public
     * @return void
     */
    public function mergeMethodsMatched(array $methods_matched)
    {
        if (!empty($methods_matched)) {
            // Merge the array with our current matched methods
            $merged = array_merge($this->methods_matched, $methods_matched);

            $this->setMethodsMatched($merged);
        }

        return $this;
    }

    /**
     * Get the buffered content
     *
     * @access public
     * @return string
     */
    public function getBufferedContent()
    {
        return $this->buffered_content;
    }

    /**
     * Set the buffered content
     *
     * @param string $buffered_content
     * @access public
     * @return MatchResult
     */
    public function setBufferedContent($buffered_content)
    {
        $this->buffered_content = (string) $buffered_content;

        return $this;
    }
}
