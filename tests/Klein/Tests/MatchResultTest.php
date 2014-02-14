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

namespace Klein\Tests;

use Klein\DataCollection\RouteCollection;
use Klein\Klein;
use Klein\MatchResult;

/**
 * MatchResultTest
 *
 * @uses AbstractKleinTest
 * @package Klein\Tests
 */
class MatchResultTest extends AbstractKleinTest
{

    protected function getTestRouteCollection()
    {
        return new RouteCollection();
    }

    public function testMatchedGetSet()
    {
        // Test functions
        $test_route_collection = $this->getTestRouteCollection();

        // Collection set in constructor
        $match_result = new MatchResult($test_route_collection);

        $this->assertSame($test_route_collection, $match_result->getMatched());

        // Collection set in method
        $match_result = new MatchResult($this->getTestRouteCollection());
        $match_result->setMatched($test_route_collection);

        $this->assertSame($test_route_collection, $match_result->getMatched());
    }

    public function testMethodsMatchedGetSet()
    {
        // Test functions
        $test_methods_matched = array(
            'POST',
            'GET',
        );

        // Not in constructor
        $match_result = new MatchResult($this->getTestRouteCollection());

        $this->assertEmpty($match_result->getMethodsMatched());

        // Collection set in constructor
        $match_result = new MatchResult($this->getTestRouteCollection(), $test_methods_matched);

        $this->assertSame($test_methods_matched, $match_result->getMethodsMatched());

        // Collection set in method
        $match_result = new MatchResult($this->getTestRouteCollection());
        $match_result->setMethodsMatched($test_methods_matched);

        $this->assertSame($test_methods_matched, $match_result->getMethodsMatched());
    }

    public function testBufferedContentGetSet()
    {
        // Test functions
        $test_buffered_content = 'doge';

        // Not in constructor
        $match_result = new MatchResult($this->getTestRouteCollection());

        $this->assertEmpty($match_result->getBufferedContent());

        // Collection set in constructor
        $match_result = new MatchResult($this->getTestRouteCollection(), array(), $test_buffered_content);

        $this->assertSame($test_buffered_content, $match_result->getBufferedContent());

        // Collection set in method
        $match_result = new MatchResult($this->getTestRouteCollection());
        $match_result->setBufferedContent($test_buffered_content);

        $this->assertSame($test_buffered_content, $match_result->getBufferedContent());
    }
}
