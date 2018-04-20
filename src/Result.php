<?php

namespace Weblab\CURL;

/**
 * Class Result - A wrapper around a cURL result
 * @author Weblab.nl - Eelco Verbeek
 */
class Result {

    /**
     * @var mixed   The body of the result of the cURL request
     */
    private $result;

    /**
     * @var int     The HTTP status code of the cURL request
     */
    private $status;

    /**
     * @var array   The return headers of the cURL request
     */
    private $headers;

    /**
     * Result constructor.
     *
     * @param   string  $result
     * @param   int     $status
     * @param   string  $headers
     */
    public function __construct($result, $status, $headers) {
        // Save the headers
        $this->setHeaders($headers);

        // If the result is in JSON decode it
        if ($this->getHeader('Content-Type') === 'application/json') {
            $this->result = json_decode($result);
        } else {
            $this->result = $result;
        }

        $this->status = $status;
    }

    /**
     * Parse and store the headers
     *
     * @param   string  $headers
     */
    private function setHeaders($headers) {
        // Make array of the different headers
        $headers = explode(PHP_EOL, $headers);

        foreach ($headers as $header) {
            // Cut the header into pieces
            $header = explode(': ', $header);

            // Skip header if it's not in the default format
            if (!is_array($header) && count($header) === 1) {
                continue;
            }

            // Get the header type
            $type = strtolower(array_shift($header));

            // Glue the header back together
            $value = implode(': ', $header);

            // Skip header if the type or value is empty
            if (empty($type) || empty($value)) {
                continue;
            }

            // Store the header
            $this->headers[$type] = trim($value);
        }
    }

    /**
     * Return the body of the cURL request
     *
     * @return  mixed|string
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * Return the HTTP status code of the cURL request
     *
     * @return  int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Return the value of a header
     *
     * @param   string      $var
     * @return  mixed|null
     */
    public function getHeader($var) {
        $var = strtolower($var);

        if (isset($this->headers[$var])) {
            return $this->headers[$var];
        }

        return null;
    }

}
