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
    public function __construct($response, array $info, array $error = array())
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
        echo "<pre>\n";
        echo "=============================================\n";
        echo "CURL REQUEST DEBUGGER\n";
        echo "=============================================\n\n";

        echo "=============================================\n";
        echo "Response (".gettype($this->response).")\n";
        echo "=============================================\n";
        echo $this->response."\n\n";

        if ($this->error) {
            echo "=============================================\n";
            echo "Errors\n";
            echo "=============================================\n";
            echo "Code: ".$this->error['code']."\n";
            echo "Message: ".$this->error['message']."\n\n";
        }

        echo "=============================================\n";
        echo "Info\n";
        echo "=============================================\n";
        foreach ($this->info as $key => $value) {
            echo ucwords(str_replace('_', ' ', $key)).": ";
            if (is_array($value)) {
                echo implode(', ', $value)."\n";
            } else {
                echo $value."\n";
            }
        }

        echo "\n</pre>";
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
     * @param boolean $strict Requires a 200-level response when truthy.
     *
     * @return boolean
     */
    public function success($strict = false)
    {
        $status_code = substr($this->status(), 0, 1);

        if ($strict) {
            return ($status_code === '2') ? true : false;
        } else {
            return ($status_code === '2' || $status_code === '3') ? true : false;
        }
    }
}
