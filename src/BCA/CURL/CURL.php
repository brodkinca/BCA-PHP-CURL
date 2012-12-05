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
 * cURL Request Class
 *
 * Work with remote servers via cURL much easier than using the native PHP bindings.
 *
 * @category  Library
 * @package   BCA/CURL
 * @author    Brodkin CyberArts <support@brodkinca.com>
 * @author    Philip Sturgeon <email@philsturgeon.co.uk>
 * @copyright 2012 Brodkin CyberArts.
 * @license   GPL-3.0 http://www.gnu.org/licenses/gpl.txt
 * @version   GIT: $Id$
 * @link      https://github.com/brodkinca/BCA-PHP-CURL
 */
class CURL
{
    /**
     * cURL Session Handler
     *
     * @var curl_init()
     */
    protected $session;

    /**
     * Base URL
     *
     * @var string
     */
    protected $url;

    /**
     * cURL Options
     *
     * @var array
     */
    protected $options = array();

    /**
     * HTTP Headers
     *
     * @var array
     */
    protected $headers = array();

    /**
     * Query Parameters
     *
     * @var array
     */
    protected $params = array();

    /**
     * Constructor
     *
     * @param string $url    Valid URL resource
     * @param array  $params Associative array of query parameters.
     */
    public function __construct($url, array $params=array())
    {
        if ( ! $this->_hasExtCurl()) {
            trigger_error('cURL Class - PHP was not built with cURL enabled. Rebuild PHP with --with-curl to use cURL.', E_USER_ERROR);
        }

        $this->params = $params;

        // Create Session
        $this->_startSession($url);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        curl_close($this->session);
    }

    /**
     * GET HTTP Request
     *
     * @return Response
     */
    public function get()
    {
        // Convert parameter array to query string
        if (!empty($this->params)) {
            $params = http_build_query($this->params, null, '&');
            $this->option(CURLOPT_URL, $this->url.'?'.$params);
        }

        $this->_method('get');

        return $this->_execute();
    }

    /**
     * POST HTTP Request
     *
     * @param string $data Raw data to send in request.
     *
     * @return Response
     */
    public function post($data=null)
    {
        if (!empty($data)) {
            $this->option(CURLOPT_POSTFIELDS, $data);

            // Convert parameter array to query string
            if (!empty($this->params)) {
                $params = http_build_query($this->params, null, '&');
                $this->option(CURLOPT_URL, $this->url.'?'.$params);
            }

        } else {
            // Convert parameter array to query string
            $params = http_build_query($this->params, null, '&');
            $this->option(CURLOPT_POSTFIELDS, $params);
        }

        $this->_method('post');

        $this->option(CURLOPT_POST, true);

        return $this->_execute();
    }

    /**
     * PUT HTTP Request
     *
     * @param string $data Raw data to send in place of parameters.
     *
     * @return Response
     */
    public function put($data=null)
    {
        if (!empty($data)) {
            $this->option(CURLOPT_POSTFIELDS, $data);

            // Convert parameter array to query string
            if (!empty($this->params)) {
                $params = http_build_query($this->params, null, '&');
                $this->option(CURLOPT_URL, $this->url.'?'.$params);
            }

        } else {
            // Convert parameter array to query string
            $params = http_build_query($this->params, null, '&');
            $this->option(CURLOPT_POSTFIELDS, $params);
        }

        $this->_method('put');

        // Overrides $_POST with PUT data
        $this->option(CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));

        return $this->_execute();
    }

    /**
     * DELETE HTTP Request
     *
     * @return Response
     */
    public function delete()
    {
        // Convert parameter array to query string
        $params = http_build_query($this->params, null, '&');
        $this->option(CURLOPT_POSTFIELDS, $params);

        $this->_method('delete');

        return $this->_execute();
    }

    /**
     * Set HTTP username and password
     *
     * @param string $username Username.
     * @param string $password Password.
     * @param string $type     Any valid value for CURLOPT_HTTPAUTH.
     *
     * @return self
     */
    public function auth($username, $password = '', $type = 'any')
    {
        $this->option(CURLOPT_HTTPAUTH, constant('CURLAUTH_' . strtoupper($type)));
        $this->option(CURLOPT_USERPWD, $username . ':' . $password);

        return $this;
    }

    /**
     * Set Session Cookies
     *
     * @param array $params Associative array of cookie keys and values.
     *
     * @return self
     */
    public function cookies(array $params = array())
    {
        if (is_array($params)) {
            $params = http_build_query($params, null, '&');
        }

        $this->option(CURLOPT_COOKIE, $params);

        return $this;
    }

    /**
     * Set HTTP Header
     *
     * @param string $header  Key of HTTP header.
     * @param string $content Value of HTTP header.
     *
     * @return self
     */
    public function header($header, $content = null)
    {
        $this->headers[] = ($content) ? $header.': '.$content : $header;

        return $this;
    }

    /**
     * Set cURL Option
     *
     * @param string $code  Key of cURL option to set or override.
     * @param string $value New value of cURL option.
     *
     * @return self
     */
    public function option($code, $value)
    {
        if (is_string($code) && !is_numeric($code)) {
            $code = constant('CURLOPT_' . strtoupper($code));
        }

        $this->options[$code] = $value;

        return $this;
    }

    /**
     * Set a Parameter
     *
     * @param string $key   Parameter key on which value should be set.
     * @param string $value Key on which value should be set.
     *
     * @return self
     */
    public function param($key, $value)
    {
        $this->params["$key"] = $value;

        return $this;
    }

    /**
     * Set Multiple Parameters via Array
     *
     * @param array $params Associative array of parameters.
     *
     * @return self
     */
    public function params(array $params)
    {
        $this->params = array_merge($params, $this->params);

        return $this;
    }

    /**
     * Force SSL Usage and Set SSL Options
     *
     * @param boolean $verify_peer  Require a valid certificate.
     * @param integer $verify_host  Require a hostname match of certificate.
     * @param string  $path_to_cert Local path to certificate(s) file.
     *
     * @return self
     */
    public function ssl($verify_peer = true, $verify_host = 2, $path_to_cert = null)
    {
        if (strpos($this->url, 'http://') === 0) {
            $stop_at = 1;
            $this->url = str_replace("http", "https", $this->url, $stop_at);
            $this->option(CURLOPT_URL, $this->url);
        }

        if ($verify_peer) {
            $this->option(CURLOPT_SSL_VERIFYPEER, true);
            $this->option(CURLOPT_SSL_VERIFYHOST, $verify_host);
            if (isset($path_to_cert)) {
                $path_to_cert = realpath($path_to_cert);
                $this->option(CURLOPT_CAINFO, $path_to_cert);
            }
        } else {
            $this->option(CURLOPT_SSL_VERIFYPEER, false);
        }

        return $this;
    }

    /**
     * Execute Request and Return Result
     *
     * @return mixed
     */
    private function _execute()
    {
        // Set two default options, and merge any extra ones in
        if (!$this->_hasOption(CURLOPT_TIMEOUT)) {
            $this->option(CURLOPT_TIMEOUT, 30);
        }
        if (!$this->_hasOption(CURLOPT_RETURNTRANSFER)) {
            $this->option(CURLOPT_RETURNTRANSFER, true);
        }
        if (!$this->_hasOption(CURLOPT_FAILONERROR)) {
            $this->option(CURLOPT_FAILONERROR, true);
        }
        if (!$this->_hasOption(CURLOPT_USERAGENT)) {
            $this->option(
                CURLOPT_USERAGENT,
                'BCA cURL http://git.io/kTMBLg'
            );
        }

        // Only set follow location if not running securely
        if (!ini_get('safe_mode') && !ini_get('open_basedir')) {
            if (!$this->_hasOption(CURLOPT_FOLLOWLOCATION)) {
                $this->option(CURLOPT_FOLLOWLOCATION, true);
            }
        }

        if (!empty($this->headers)) {
            $this->option(CURLOPT_HTTPHEADER, $this->headers);
        }

        curl_setopt_array($this->session, $this->options);

        // Execute the request & and hide all output
        $response = curl_exec($this->session);
        $info = curl_getinfo($this->session);

        if ($response === false) {
            // Request failed
            $error['code'] = curl_errno($this->session);
            $error['message'] = curl_error($this->session);

            return new Response($response, $info, $error);

        } else {
            return new Response($response, $info);
        }
    }

    /**
     * Was PHP Built with cURL Support?
     *
     * @return boolean
     */
    private function _hasExtCurl()
    {
        return function_exists('curl_init');
    }

    /**
     * Does Instance Have a Given Option Set?
     *
     * @param int $code Integer representation of cURL option.
     *
     * @return boolean
     */
    private function _hasOption($code)
    {
        return isset($this->options[$code]);
    }

    /**
     * Does Instance Have a Given Option Set?
     *
     * @param string $key Key to check in parameter array.
     *
     * @return boolean
     */
    private function _hasParam($key)
    {
        return isset($this->params["$key"]);
    }

    /**
     * Set HTTP Method
     *
     * @param string $method Valid HTTP method.
     *
     * @return self
     */
    private function _method($method)
    {
        $this->option(CURLOPT_CUSTOMREQUEST, strtoupper($method));

        return $this;
    }

    /**
     * Set cURL Multiple Options by Array
     *
     * @param array $options Associative array of options and values.
     *
     * @return self
     */
    private function _options(array $options)
    {
        foreach ($options as $key => $value) {
            $this->option($key, $value);
        }

        return $this;
    }

    /**
     * Start cURL Session
     *
     * @param string $url Valid URL resource.
     *
     * @return self
     */
    private function _startSession($url)
    {
        $this->url = $url;
        $this->session = curl_init($this->url);

        return $this;
    }

}
