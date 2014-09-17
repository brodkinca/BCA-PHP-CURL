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
     * @covers BCA\CURL\CURL::__construct
     * @covers BCA\CURL\CURL::startSession
     * @covers BCA\CURL\CURL::hasExtCurl
     * @covers BCA\CURL\CURL::hasOption
     * @covers BCA\CURL\CURL::method
     * @covers BCA\CURL\CURL::execute
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
     * @covers BCA\CURL\CURL::__construct
     * @covers BCA\CURL\CURL::startSession
     * @covers BCA\CURL\CURL::hasExtCurl
     * @covers BCA\CURL\CURL::hasOption
     * @covers BCA\CURL\CURL::method
     * @covers BCA\CURL\CURL::execute
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
     * @covers BCA\CURL\CURL::__construct
     * @covers BCA\CURL\CURL::startSession
     * @covers BCA\CURL\CURL::hasExtCurl
     * @covers BCA\CURL\CURL::hasOption
     * @covers BCA\CURL\CURL::method
     * @covers BCA\CURL\CURL::execute
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
     * @covers BCA\CURL\CURL::__construct
     * @covers BCA\CURL\CURL::startSession
     * @covers BCA\CURL\CURL::hasExtCurl
     * @covers BCA\CURL\CURL::hasOption
     * @covers BCA\CURL\CURL::method
     * @covers BCA\CURL\CURL::execute
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
        $cookies = array('foo'=>'bar', 'aaa'=>'bbb');
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->cookies($cookies)->get();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_COOKIE->foo);
        $this->assertEquals('bbb', $response->_COOKIE->aaa);
    }

    /**
     * @covers BCA\CURL\CURL::header
     * @covers BCA\CURL\CURL::execute
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
    public function testOptionConstant()
    {
        // Named option
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request
            ->option(CURLOPT_FILETIME, true)
            ->get();
        $response->debug();
        $this->expectOutputRegex("/Filetime/");
    }

    /**
     * @covers BCA\CURL\CURL::option
     */
    public function testOptionString()
    {
        // Named option, unprefixed string
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request
            ->option('FILETIME', true)
            ->get();
        $response->debug();
        $this->expectOutputRegex("/Filetime/");
    }

    /**
     * @covers BCA\CURL\CURL::get
     * @covers BCA\CURL\CURL::post
     * @covers BCA\CURL\CURL::put
     * @covers BCA\CURL\CURL::delete
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
     * @covers BCA\CURL\CURL::get
     * @covers BCA\CURL\CURL::post
     * @covers BCA\CURL\CURL::put
     * @covers BCA\CURL\CURL::delete
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

        // POST with raw data
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->params($params)->post('foobar');
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('foobar', $response->_RAW);
        $this->assertEquals('bar', $response->_GET->foo);
        $this->assertEquals('baz', $response->_GET->bar);

        // PUT
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->params($params)->put();
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('bar', $response->_PUT->foo);
        $this->assertEquals('baz', $response->_PUT->bar);

        // PUT with raw data
        $request = new CURL(REMOTE_TEST_SERVER);
        $response = $request->params($params)->put('foobar');
        $this->assertTrue($response->success());
        $response = json_decode($response);
        $this->assertEquals('foobar', $response->_RAW);
        $this->assertEquals('bar', $response->_GET->foo);
        $this->assertEquals('baz', $response->_GET->bar);

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
     * @group  ssl
     */
    public function testSsl()
    {
        // No parameters
        $request = new CURL(REMOTE_TEST_SERVER_SSL);
        $request->option(CURLOPT_CAINFO, SSL_CERT_PATH);
        $response = $request->ssl()->get();
        $response->debug();
        $this->assertTrue($response->success());
        $response = json_decode($response);

        // Don't verify peer
        $request = new CURL(REMOTE_TEST_SERVER_SSL);
        $response = $request->ssl(false, false)->get();
        $this->assertTrue($response->success());

        // Verify peer and host
        $request = new CURL('http://example.com');
        $response = $request->ssl(true, 2)->get();
        $this->assertFalse($response->success());

        // Verify peer and host with CA path
        $request = new CURL('http://example.com');
        $response = $request->ssl(true, 2, '/dev/null')->get();
        $this->assertFalse($response->success());
    }
}
