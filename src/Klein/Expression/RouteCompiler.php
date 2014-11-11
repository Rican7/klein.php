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

use Klein\Exceptions\RegularExpressionCompilationException;
use Klein\Klein;
use Klein\Route;

/**
 * RouteCompiler
 *
 * The default route compiler
 */
class RouteCompiler implements RouteCompilerInterface
{

    /**
     * Constants
     */

    /**
     * The regular expression used to escape the non-named param section of a route URL
     *
     * @todo Define the constant here after the Klein class's value is removed
     * @type string
     */
    const ESCAPE_REGEX = Klein::ROUTE_ESCAPE_REGEX;

    /**
     * The regular expression used to compile and match URL's
     *
     * @todo Define the constant here after the Klein class's value is removed
     * @type string
     */
    const COMPILE_REGEX = Klein::ROUTE_COMPILE_REGEX;


    /**
     * Properties
     */

    /**
     * The regular expressions to be used for each match "block" type
     *
     * Examples of these blocks are as follows:
     *
     * - integer:       '[i:id]'
     * - alphanumeric:  '[a:username]'
     * - hexadecimal:   '[h:color]'
     * - slug:          '[s:article]'
     *
     * @type array
     */
    protected $match_types = array(
        'i'  => '[0-9]++',
        'a'  => '[0-9A-Za-z]++',
        'h'  => '[0-9A-Fa-f]++',
        's'  => '[0-9A-Za-z-_]++',
        '*'  => '.+?',
        '**' => '.++',
        ''   => '[^/]+?'
    );


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param array $match_types    The regular expressions to use for each match "block" type
     */
    public function __construct(array $match_types = null)
    {
        if (null !== $match_types) {
            $this->match_types = $match_types;
        }
    }

    /**
     * Compile a route into a regular expression
     *
     * @param Route $route  The input route to compile
     * @return string       The compiled regular expression
     */
    public function compile(Route $route)
    {
        $path = $route->getPath();

        /**
         * Remove a trailing exclamation (used to negate)
         *
         * TODO: Remove this once our RouteFactory processes negations
         * and sets a boolean property for negation instead of using
         * strings as flags. This will be a BC break, so we'll have to
         * wait till the next major version.
         */
        if (isset($path[0]) && $path[0] === '!') {
            $path = substr($path, 1);
        }

        // First escape all of the non-named param (non [block]s) for regex-chars
        $escaped = preg_replace_callback(
            static::ESCAPE_REGEX,
            function ($match) {
                return preg_quote($match[0]);
            },
            $path
        );

        // Get a local reference of the match types to pass into our closure
        $match_types = $this->match_types;

        // Now let's actually compile the path
        $compiled = preg_replace_callback(
            static::COMPILE_REGEX,
            function ($match) use ($match_types) {
                list(, $pre, $type, $param, $optional) = $match;

                if (isset($match_types[$type])) {
                    $type = $match_types[$type];
                }

                // Older versions of PCRE require the 'P' in (?P<named>)
                $pattern = '(?:'
                         . ($pre !== '' ? $pre : null)
                         . '('
                         . ($param !== '' ? "?P<$param>" : null)
                         . $type
                         . '))'
                         . ($optional !== '' ? '?' : null);

                return $pattern;
            },
            $escaped
        );

        $regex = "`^$compiled$`";

        // Check if our regular expression is valid
        $this->validateRegularExpression($regex);

        return $regex;
    }

    /**
     * Validate a regular expression
     *
     * This simply checks if the regular expression is able to be compiled
     * and converts any warnings or notices in the compilation to an exception
     *
     * @param string $regex                          The regular expression to validate
     * @throws RegularExpressionCompilationException If the expression can't be compiled
     * @return boolean
     */
    private static function validateRegularExpression($regex)
    {
        $error_string = null;

        // Set an error handler temporarily
        set_error_handler(
            function ($errno, $errstr) use (&$error_string) {
                $error_string = $errstr;
            },
            E_NOTICE | E_WARNING
        );

        if (false === preg_match($regex, null) || !empty($error_string)) {
            // Remove our temporary error handler
            restore_error_handler();

            throw new RegularExpressionCompilationException(
                $error_string,
                preg_last_error()
            );
        }

        // Remove our temporary error handler
        restore_error_handler();

        return true;
    }
}
