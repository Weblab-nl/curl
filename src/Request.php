<?php

namespace Weblab\CURL;

/**
 * Class Request - A wrapper class for the PHP cURL functionality
 * @author Weblab.nl - Eelco Verbeek
 */
class Request {

    /**
     * @var array   Holds all the different CURLOPT settings
     */
    protected $settings = [
        CURLOPT_DEFAULT_PROTOCOL => 'http',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_HEADER => true,
        CURLOPT_CONNECTTIMEOUT => 10
    ];

    /**
     * @var mixed   Holds the result of the cURL
     */
    private $result;

    /**
     * Variable to hold the headers that will be sent with the cURL request
     *
     * @var array
     */
    private $headers = [];

    /**
     * Request constructor.
     */
    public function __construct() {}

    /**
     * Stores a CURLOPT setting and return $this for chaining
     *
     * @param   int     $option     See http://php.net/manual/en/function.curl-setopt.php for possible values
     * @param   mixed   $value      Value to be set
     * @return  Request
     */
    public function setOption($option, $value) {
        $this->settings[$option] = $value;
        return $this;
    }

    /**
     * Set the URL that will be called
     *
     * @param   string  $url
     * @return  Request
     */
    public function setURL($url) {
        return $this->setOption(CURLOPT_URL, $url);
    }

    /**
     * Set the request method type
     *
     * @param   string  $method
     * @return  Request
     */
    public function setRequestMethod($method) {
        return $this->setOption(CURLOPT_CUSTOMREQUEST, strtoupper($method));
    }

    /**
     * Set post fields
     *
     * @param   array|string   $value  Array should be in key => value format. String === json
     * @return  Request
     */
    public function setPostFields($value) {
        if (is_array($value)) {
            return $this->setOption(CURLOPT_POSTFIELDS, http_build_query($value));
        } else {
            return $this->setOption(CURLOPT_POSTFIELDS, $value);
        }
    }

    /**
     * Set to true to emulate application/x-www-form-urlencoded (a normal form POST)
     *
     * @param   bool    $value
     * @return  Request
     */
    public function setPost($value) {
        return $this->setOption(CURLOPT_POST, $value);
    }

    /**
     * Set to true to follow redirects. See setMaxRedirects()
     *
     * @param   bool    $value
     * @return  Request
     */
    public function setFollowRedirects($value) {
        return $this->setOption(CURLOPT_FOLLOWLOCATION, $value);
    }

    /**
     * Number of maximum redirect to follow. See setFollowRedirects()
     *
     * @param   int     $value
     * @return  Request
     */
    public function setMaxRedirects($value) {
        return $this->setOption(CURLOPT_MAXREDIRS, $value);
    }

    /**
     * Set to false to not check if the SSL certificate is valid
     *
     * @param   bool    $value
     * @return  Request
     */
    public function setSslVerifyPeer($value) {
        return $this->setOption(CURLOPT_SSL_VERIFYPEER, $value);
    }

    /**
     * Set max timeout to connect to the host
     *
     * @param   int     $seconds
     * @return  Request
     */
    public function setConnectTimeout($seconds) {
        return $this->setOption(CURLOPT_CONNECTTIMEOUT, $seconds);
    }

    /**
     * Set the port it should try to connect to
     *
     * @param   int     $port
     * @return  Request
     */
    public function setPort($port) {
        return $this->setOption(CURLOPT_PORT, $port);
    }

    /**
     * Set max timout for the host to return all data
     *
     * @param   int     $seconds
     * @return  Request
     */
    public function setTimeOut($seconds) {
        return $this->setOption(CURLOPT_TIMEOUT, $seconds);
    }

    /**
     * Set authorization header bearer token
     *
     * @param $token
     * @return mixed
     */
    public function setBearer($token) {
        return $this->setHeader('Authorization', 'Bearer ' . $token);
    }

    /**
     * Set a header that will be sent with the cURL. Key and value will be concatenated with a ":" later. For example: $key = "Authorization" $value = "Bearer iugi342ig3434iyg34ig" will become "Authorization : Bearer iugi342ig3434iyg34ig"
     *
     * @param   string  $key
     * @param   string  $value
     * @return  $this
     */
    public function setHeader($key, $value) {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Do a GET request
     *
     * @param   string  $url            The URL you want to make a request to
     * @param   array   $parameters     Must be passed as key => value
     * @return  Result
     */
    public function get($url, $parameters = []) {
        $this->setOption(CURLOPT_HTTPGET, true);
        $this->setRequestMethod('GET');
        $this->setURL($this->buildURL($url, $parameters));

        return $this->run();
    }

    /**
     * Do a POST request
     *
     * @param   string  $url            The URL you want to make a request to
     * @param   array   $parameters     Must be passed as key => value
     * @return  Result
     */
    public function post($url, $parameters = []) {
        $this->setURL($url);
        $this->setOption(CURLOPT_POST, true);
        $this->setRequestMethod('POST');
        $this->setPostFields($parameters);
        $this->setPost(true);

        return $this->run();
    }

    /**
     * Do a PUT request
     *
     * @param   string  $url            The URL you want to make a request to
     * @param   array   $parameters     Must be passed as key => value
     * @return  Result
     */
    public function put($url, $parameters = []) {
        $this->setURL($url);
        $this->setRequestMethod('PUT');
        $this->setPostFields($parameters);
        $this->setPost(true);

        return $this->run();
    }

    /**
     * Do a PATCH request
     *
     * @param   string  $url            The URL you want to make a request to
     * @param   array   $parameters     Must be passed as key => value
     * @return  Result
     */
    public function patch($url, $parameters = []) {
        $this->setURL($url);
        $this->setRequestMethod('PATCH');
        $this->setPostFields($parameters);
        $this->setPost(true);

        return $this->run();
    }

    /**
     * Do a DELETE request
     *
     * @param   string  $url            The URL you want to make a request to
     * @param   array   $parameters     Must be passed as key => value
     * @return  Result
     */
    public function delete($url, $parameters = []) {
        $this->setRequestMethod('DELETE');
        $this->setURL($this->buildURL($url, $parameters));

        return $this->run();
    }

    /**
     * Helper function to build an URL with parameters
     *
     * @param   string  $url            The URL you want to make a request to
     * @param   array   $parameters     Must be passed as key => value
     * @return  string
     */
    protected function buildURL($url, $parameters = []) {
        return $url . '?' . http_build_query($parameters);
    }

    /**
     * Checks if a file exists
     */
    public function doesFileExist($url) {
        // Setup connection
        $ch = curl_init($url);

        // We don't want to download the file, we just want to know if the file exists
        curl_setopt($ch, CURLOPT_NOBODY, true);

        // Execute cURL
        curl_exec($ch);

        // Get the HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close the connection
        curl_close($ch);

        // If status 200 the file exists, so return true in that case, otherwise false
        return $httpCode == 200;
    }

    /**
     * Actually executes the cURL request
     *
     * @return Result
     */
    public function run() {
        // Setup connection
        $resource = curl_init();

        // Set cURL options
        foreach ($this->settings as $option => $setting) {
            curl_setopt($resource, $option, $setting);
        }

        // Process headers
        $headers = [];
        foreach ($this->headers as $key => $value) {
            if (!empty($value)) {
                $headers[] = implode(' : ', [$key, $value]);
            }
            else {
                $this->headers[] = $key;
            }
        }

        // Add the headers
        curl_setopt($resource, CURLOPT_HTTPHEADER, $headers);

        // Execute cURL
        $response = curl_exec($resource);

        // Get the size of the header
        $header_size = curl_getinfo($resource, CURLINFO_HEADER_SIZE);

        // Get the header from the result
        $headers = substr($response, 0, $header_size);

        // Get the body from the result
        $body = substr($response, $header_size);

        // Get the HTTP response code from the result
        $responseCode = curl_getinfo($resource, CURLINFO_HTTP_CODE);

        // Close the connection
        curl_close($resource);

        // Create Result instance
        $this->result = new Result($body, $responseCode, $headers);

        return $this->result;
    }

    /**
     * Return the result
     *
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }
    
}
