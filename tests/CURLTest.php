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
 * cURL Request Class Tests
 *
 * @category  Test
 * @package   BCA/CURL
 * @author    Brodkin CyberArts <support@brodkinca.com>
 * @copyright 2012 Brodkin CyberArts.
 * @license   GPL-3.0 http://www.gnu.org/licenses/gpl.txt
 * @link      https://github.com/brodkinca/BCA-PHP-CURL
 */
class CURLTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers BCA\CURL\CURL::get
     * @covers BCA\CURL\CURL::_startSession
     * @covers BCA\CURL\CURL::_options
     * @covers BCA\CURL\CURL::_execute
     */
    public function testGet()
    {
        $invalid_url = new CURL('http://example.invalid/');
        $response = $invalid_url->get();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertFalse($response->success());

        $error_url = new CURL(REMOTE_TEST_SERVER.'?http_code=500');
        $response = $error_url->get();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertFalse($response->success());

        $good_url = new CURL(REMOTE_TEST_SERVER);
        $response = $good_url->get();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertTrue($response->success());
        $expected = file_get_contents(REMOTE_TEST_SERVER);
        $this->assertEquals($expected, $response);
    }

    /**
     * @covers BCA\CURL\CURL::post
     * @covers BCA\CURL\CURL::_startSession
     * @covers BCA\CURL\CURL::_options
     * @covers BCA\CURL\CURL::_execute
     */
    public function testPost()
    {
        $invalid_url = new CURL('http://example.invalid/');
        $response = $invalid_url->post();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertFalse($response->success());

        $error_url = new CURL(REMOTE_TEST_SERVER.'?http_code=500');
        $response = $error_url->post();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertFalse($response->success());

        $good_url = new CURL(REMOTE_TEST_SERVER);
        $response = $good_url->post();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertTrue($response->success());
        $expected = file_get_contents(REMOTE_TEST_SERVER);
        $this->assertEquals($expected, $response);

        $good_url = new CURL(REMOTE_TEST_SERVER);
        $response = $good_url->post('foobar');
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('foobar', $response->_RAW);

    }

    /**
     * @covers BCA\CURL\CURL::put
     * @covers BCA\CURL\CURL::_startSession
     * @covers BCA\CURL\CURL::_options
     * @covers BCA\CURL\CURL::_execute
     */
    public function testPut()
    {
        $invalid_url = new CURL('http://example.invalid/');
        $response = $invalid_url->put();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertFalse($response->success());

        $error_url = new CURL(REMOTE_TEST_SERVER.'?http_code=500');
        $response = $error_url->put();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertFalse($response->success());

        $good_url = new CURL(REMOTE_TEST_SERVER);
        $response = $good_url->put();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertTrue($response->success());
        $expected = file_get_contents(REMOTE_TEST_SERVER);
        $this->assertEquals($expected, $response);

        $good_url = new CURL(REMOTE_TEST_SERVER);
        $response = $good_url->put('foobar');
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('foobar', $response->_RAW);
    }

    /**
     * @covers BCA\CURL\CURL::delete
     * @covers BCA\CURL\CURL::_startSession
     * @covers BCA\CURL\CURL::_options
     * @covers BCA\CURL\CURL::_execute
     */
    public function testDelete()
    {
        $invalid_url = new CURL('http://example.invalid/');
        $response = $invalid_url->delete();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertFalse($response->success());

        $error_url = new CURL(REMOTE_TEST_SERVER.'?http_code=500');
        $response = $error_url->delete();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertFalse($response->success());

        $good_url = new CURL(REMOTE_TEST_SERVER);
        $response = $good_url->delete();
        $this->assertInstanceOf('\BCA\CURL\Response', $response);
        $this->assertTrue($response->success());
        $expected = file_get_contents(REMOTE_TEST_SERVER);
        $this->assertEquals($expected, $response);
    }

    /**
     * @covers BCA\CURL\CURL::auth
     */
    public function testAuth()
    {
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->auth('foo', 'bar', 'basic')->get();
        $this->assertTrue($response->success());

        $response = json_decode($response);
        $this->assertEquals('foo', $response->auth_user);
        $this->assertEquals('bar', $response->auth_pass);
    }

    /**
     * @covers BCA\CURL\CURL::cookies
     */
    public function testCookies()
    {
        $cookies = array('foo'=>'bar');
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->cookies($cookies)->get();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_COOKIE->foo);
    }

    /**
     * @covers BCA\CURL\CURL::header
     */
    public function testHeader()
    {
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->header('X-CURL-TEST', 'foobar')->get();
        $this->assertTrue($response->success());

        $response = json_decode($response);
        $this->assertEquals('foobar', $response->headers->X_CURL_TEST);
    }

    /**
     * @covers BCA\CURL\CURL::option
     */
    public function testOption()
    {
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->option(CURLOPT_CONNECTTIMEOUT_MS, 1)->get();
        $this->assertFalse($response->success());
    }

    /**
     * @covers BCA\CURL\CURL::param
     */
    public function testParam()
    {
        // GET
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->param('foo', 'bar')->get();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_GET->foo);

        // POST
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->param('foo', 'bar')->post();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_POST->foo);

        // PUT
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->param('foo', 'bar')->put();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_PUT->foo);

        // DELETE
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->param('foo', 'bar')->delete();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_DELETE->foo);
    }

    /**
     * @covers BCA\CURL\CURL::params
     */
    public function testParams()
    {
        $params['foo'] = 'bar';
        $params['bar'] = 'baz';

        // GET
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->params($params)->get();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_GET->foo);
        $this->assertEquals('baz', $response->_GET->bar);

        // POST
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->params($params)->post();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_POST->foo);
        $this->assertEquals('baz', $response->_POST->bar);

        // PUT
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->params($params)->put();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_PUT->foo);
        $this->assertEquals('baz', $response->_PUT->bar);

        // DELETE
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->params($params)->delete();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_DELETE->foo);
        $this->assertEquals('baz', $response->_DELETE->bar);
    }

    /**
     * @covers BCA\CURL\CURL::ssl
     */
    public function testSsl()
    {
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->ssl(true)->get();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('https', $response->headers->X_FORWARDED_PROTO);
    }
}
