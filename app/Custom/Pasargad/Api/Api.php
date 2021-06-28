<?php

namespace App\Custom\Pasargad\Api;

class Api {

    protected $_api_key;
    protected $_timeout = 30;
    protected $_verify_ssl = true;
    protected $_verify_host = 2;
    protected $curlErrno = false;
    protected $curlError = false;
    protected $curlProxy = false;
    protected $response = false;
    protected $request = array();
    protected $curlInfo;
    protected $_curl_callback;

    public function __construct()
    {
        if (!function_exists('curl_init'))
        {
            throw new CurlException ("cURL is not available. This API wrapper cannot be used.");
        }
    }

    public function setApiKey($key)
    {
        $this->_api_key = $key;
    }

    public function enableSslVerification()
    {
        $this->_verify_ssl = true;
        $this->_verify_host = 2;
    }

    public function disableSslVerification()
    {
        $this->_verify_ssl = false;
        $this->_verify_host = 0;
    }

    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;
    }

    public function setCurlProxy($proxy)
    {
        $this->curlProxy = $proxy;
    }

    public function setCurlCallback($callback)
    {
        $this->_curl_callback = $callback;
    }

    public function send($url, $params, $sign = "")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $headers[] = 'Sign: '.$sign;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);



        $this->request['headers'] = $headers;
        $this->request['params'] = $params;

        $this->response = curl_exec($ch);

        $this->curlInfo = curl_getinfo($ch);
        curl_close($ch);
        return  $this->response;
    }

    private function _parseHeaders($raw_headers)
    {
        if (!function_exists('http_parse_headers')) {
            $headers = array();
            $key = '';

            foreach(explode("\n", $raw_headers) as $i => $h) {
                $h = explode(':', $h, 2);

                if (isset($h[1])) {
                    if (!isset($headers[$h[0]]))
                        $headers[$h[0]] = trim($h[1]);
                    elseif (is_array($headers[$h[0]])) {
                        $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                    }
                    else {
                        $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                    }
                    $key = $h[0];
                }
                else {
                    if (substr($h[0], 0, 1) == "\t")
                        $headers[$key] .= "\r\n\t".trim($h[0]);
                    elseif (!$key)
                        $headers[0] = trim($h[0]);
                }
            }
            return $headers;

        } else {
            return http_parse_headers($raw_headers);
        }
    }

    private function _getHeaders()
    {
        return $this->_parseHeaders(substr($this->response, 0, $this->curlInfo['header_size']));
    }

    private function _getBody()
    {
        return substr($this->response, $this->curlInfo['header_size']);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getCurlInfo()
    {
        return $this->curlInfo;
    }

    public function isCurlError ()
    {
        return (bool) $this->curlErrno;
    }

    public function getCurlErrno ()
    {
        return $this->curlErrno;
    }

    public function getCurlError ()
    {
        return $this->curlError;
    }

}
