<?php

/**
 * cURL Library
 *
 * PHP Version 5.3
 *
 * @category  Library
 * @package   BCA/CURL
 * @author    Brodkin CyberArts <support@brodkinca.com>
 * @copyright 2012 Brodkin CyberArts.
 * @license   GPL-3.0 http://www.gnu.org/licenses/gpl.txt
 * @version   GIT: $Id$
 * @link      https://github.com/brodkinca/BCA-PHP-CURL
 */

namespace BCA\CURL;

/**
 * cURL Response Class Tests
 *
 * @category  Test
 * @package   BCA/CURL
 * @author    Brodkin CyberArts <support@brodkinca.com>
 * @copyright 2012 Brodkin CyberArts.
 * @license   GPL-3.0 http://www.gnu.org/licenses/gpl.txt
 * @link      https://github.com/brodkinca/BCA-PHP-CURL
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    protected $object;
    protected $dataResponse = 'foobar';
    protected $dataInfo = array('foo'=>'bar', 'http_code'=>500);

    /**
     * Setup Each Test
     *
     * @return null
     */
    public function setUp()
    {
        $error = array('code' => 6, 'message' => CURLE_COULDNT_RESOLVE_HOST);

        $this->object = new Response(
            $this->dataResponse, 
            $this->dataInfo, 
            $error
        );
    }

    /**
     * @covers BCA\CURL\Response::__get
     * @covers BCA\CURL\Response::__construct
     */
    public function test__get()
    {
        // Key returns value with correct type
        $this->assertSame('bar', $this->object->foo);

        // Non-existent key return null
        $this->assertSame(null, $this->object->noexists);

        // HTTP status code
        $this->assertSame(500, $this->object->http_code);
    }

    /**
     * @covers BCA\CURL\Response::__toString
     * @covers BCA\CURL\Response::__construct
     */
    public function test__toString()
    {
        $this->assertEquals($this->dataResponse, $this->object);
    }

    /**
     * @covers BCA\CURL\Response::debug
     * @covers BCA\CURL\Response::__construct
     */
    public function test_debug()
    {
        $this->expectOutputRegex("/Debugger/");
        $this->assertNull($this->object->debug());

        $this->expectOutputRegex("/Errors/");
    }

    /**
     * @covers BCA\CURL\Response::status
     * @covers BCA\CURL\Response::__construct
     */
    public function test_status()
    {
        $this->assertSame(500, $this->object->status());
    }

    /**
     * @covers BCA\CURL\Response::success
     * @covers BCA\CURL\Response::__construct
     */
    public function test_success()
    {
        $info = $this->dataInfo;
        $info['http_code'] = 500;
        $response = new Response($this->dataResponse, $info);
        $this->assertFalse($response->success());

        $info = $this->dataInfo;
        $info['http_code'] = 200;
        $response = new Response($this->dataResponse, $info);
        $this->assertTrue($response->success());

        $info = $this->dataInfo;
        $info['http_code'] = 300;
        $response = new Response($this->dataResponse, $info);
        $this->assertTrue($response->success());

        $info = $this->dataInfo;
        $info['http_code'] = 300;
        $response = new Response($this->dataResponse, $info);
        $this->assertFalse($response->success(true));
    }
}
