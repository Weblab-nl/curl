<?php

namespace Weblab\CURL;

/**
 * Class CURL - Basically a factory to create CURL\Request instances
 * @author Weblab.nl - Eelco Verbeek
 */
class CURL {

    /**
     * Creates an CURL\Request instance and acts like a proxy
     *
     * @param   string          $method
     * @param   mixed           $parameters
     * @return  Request|Result
     */
    public static function __callStatic($method, $parameters) {
        return call_user_func_array([new Request, $method], $parameters);
    }

}
