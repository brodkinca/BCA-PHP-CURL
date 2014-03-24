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
 * cURL Response Class
 *
 * @category  Library
 * @package   BCA/CURL
 * @author    Brodkin CyberArts <support@brodkinca.com>
 * @copyright 2012 Brodkin CyberArts.
 * @license   GPL-3.0 http://www.gnu.org/licenses/gpl.txt
 * @link      https://github.com/brodkinca/BCA-PHP-CURL
 */
class Response
{
    /**
     * Raw Response Data
     *
     * @var string
     */
    private $response;

    /**
     * cURL Stats Returned by curlinfo()
     *
     * @var array
     */
    private $info;

    /**
     * cURL Error Information
     *
     * @var array
     */
    private $error;

    /**
     * Populate Data
     *
     * @param string $response Data received from cURL request.
     * @param array  $info     Array returned from curl_getinfo().
     * @param array  $error    Array of error data, if applicable.
     */
    public function __construct($response, array $info, array $error=array())
    {
        settype($response, 'string');

        $this->response = $response;
        $this->info = $info;
        $this->error = $error;
    }

    /**
     * Get cURL Response Data
     *
     * @param string $key Key returned by curl_getinfo().
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->info["$key"])) {
            return $this->info["$key"];
        }

        return null;
    }

    /**
     * Return Response as String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->response;
    }

    /**
     * Print Debug Message to Screen
     *
     * @return null
     */
    public function debug()
    {
        echo "=============================================<br/>\n";
        echo "<h2>CURL Request Debugger</h2>\n";
        echo "=============================================<br/>\n";
        echo "<h3>Response</h3>\n";
        echo "<code>" . nl2br(htmlentities($this->response)) . "</code><br/>\n\n";

        if ($this->error) {
            echo "=============================================<br/>\n";
            echo "<h3>Errors</h3>";
            echo "<strong>Code:</strong> ".$this->error['code']."<br/>\n";
            echo "<strong>Message:</strong> ".$this->error['message']."<br/>\n";
        }

        echo "=============================================<br/>\n";
        echo "<h3>Info</h3>";
        echo "<pre>";
        print_r(!empty($this->info));
        echo "</pre>";
    }

    /**
     * HTTP Status Code of Response
     *
     * @return int
     */
    public function status()
    {
        return $this->info['http_code'];
    }

    /**
     * Was Request Successful
     *
     * @param boolean $strict Requires a 200-level response whem truthy.
     *
     * @return boolean
     */
    public function success($strict=false)
    {
        $status_code = substr($this->status(), 0, 1);

        if ($strict) {
            return ($status_code === '2') ? true : false;
        } else {
            return ($status_code === '2' || $status_code === '3') ? true : false;
        }
    }

}
