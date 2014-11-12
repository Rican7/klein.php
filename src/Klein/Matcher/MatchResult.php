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

/**
 * MatchResult
 *
 * The result of a match
 */
class MatchResult
{

    /**
     * Properties
     */

    /**
     * Whether or not the path was a match
     *
     * @type boolean
     */
    protected $is_path_match;

    /**
     * Whether or not the method was a match
     *
     * @type boolean
     */
    protected $is_method_match;

    /**
     * The parameter map from the resulting match
     *
     * @type array
     */
    protected $params;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param boolean $is_path_match
     * @param boolean $is_method_match
     * @param array $params
     */
    public function __construct($is_path_match, $is_method_match, array $params)
    {
        $this->is_path_match = (bool) $is_path_match;
        $this->is_method_match = (bool) $is_method_match;
        $this->params = $params;
    }

    /**
     * Check if the path was a match
     *
     * @return boolean
     */
    public function isPathMatch()
    {
        return $this->is_path_match;
    }

    /**
     * Check if the method was a match
     *
     * @return boolean
     */
    public function isMethodMatch()
    {
        return $this->is_method_match;
    }

    /**
     * Check if the match was a success
     *
     * This checks if both the path AND method were matched
     *
     * @return boolean
     */
    public function isMatch()
    {
        return $this->is_path_match && $this->is_method_match;
    }

    /**
     * Get the parameter map from the resulting match
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
