<?php

namespace App\Custom\Pasargad;


use App\Custom\Pasargad\Api\Api;
use App\Custom\Pasargad\RSA\RSAProcessor;

class Pasargad  extends Api{
    private $merchantCode = 4483845;
    private $terminalCode = 1664157;
    private $redirectAddress = "http://academy.honari.com/callback?site=pasargad";
    private $certificate = '<RSAKeyValue><Modulus>u9QiaSVNXNcO2Ist3TJOPLKhNsV84FVNByhctGGWDpUhwrTx7x1gc+HM8PCnDTXHpmPzo08yQNqt0gjFPHjo1zitu4DIWilOmYeMNXRYYpn5mmZiBlCIkw7z7mc0kPoVLAdExk6FU0R8q8cy5xREbJpEfvLm+aj5Y+BtfPLokm0=</Modulus><Exponent>AQAB</Exponent><P>6iKdX28GXw5KjbthcCwnX9+NvFJuZ1Nn4uLUEAw0j/3eM3Zp5Fg97FJILBoXlU8pPQKj8h+YESD6UBp1eqHDQw==</P><Q>zV58S6HN/IVgDFxG72o13a57gpTBOV+KEFF88R8s5+mhDyLzD4s8Vf/IJfV3xcJeOckKMleMAYE9JlKYnTmAjw==</Q><DP>xZsRV0pNBkz5f0V2p0Wctb3n0dmAdJRgSY1HjYO/mQeaUbTPCnmvSZTodNBQtyNomqVv2RnxLgO3P4QVQrrkIQ==</DP><DQ>eZHEDFV1BVXCvK5nQ1RhHKAr9umt1BOtO+mxB19ICuSu9bHfpkTq65GlXmsHgqaDdrt+cLyIYV+q3iOoufGPGw==</DQ><InverseQ>ElTK3vHaTTYISddW9YQPOZlEWB7A/Xn3oV+y5SDPg3vAOegmhNGrE9qekJB1XaIgqCLTU6A71NXLhDOBrDHNcw==</InverseQ><D>jEsT9MN2+Gxt21KBzGFBzNaD0fxKnOk54qnELLtjMLs1f1BWEQs5OvUidajareRInsCzf3ytBYIRKPuCDvwktSyJ4MtYC+oxwTq9vo8NqKFyevYpK2gkwfSO+Ar5u3GZmh1ABy46C3QxzPH+lwxutnX7TMOVBs0HidYXQrX9R4U=</D></RSAKeyValue>';

    // Do not edit below.
    private $url = 'https://pep.shaparak.ir/Api/v1/Payment/';
    private $action = "1003";


//    private function add_PKCS1_padding($data, $blocksize)
//    {
//        $pad_length = $blocksize - 3 - strlen($data);
//        $block_type = "\x01";
//        $padding = str_repeat("\xFF", $pad_length);
//        return "\x00" . $block_type . $padding . "\x00" . $data;
//    }

    public function createSign($data)
    {
        $processor = new RSAProcessor($this->certificate , RSAProcessor::XMLString);
        return base64_encode($processor->sign(sha1($data, true)));
    }

    public function getToken($params)
    {
        $params['action'] = $this->action;
        $params['merchantCode'] = $this->merchantCode;
        $params['terminalCode'] = $this->terminalCode;
        $params['redirectAddress'] = $this->redirectAddress;
        $params['timeStamp'] = date("Y/m/d H:i:s");

        $sign = $this->createSign(json_encode($params));

        $respose = $this->send($this->url . 'GetToken', $params, $sign);

        return json_decode($respose);
    }

    public function checkTransactionResult($params)
    {
        $params['merchantCode'] = $this->merchantCode;
        $params['terminalCode'] = $this->terminalCode;
        $params['timeStamp'] = date("Y/m/d H:i:s");

        $respose = $this->send($this->url . 'CheckTransactionResult', $params);

        return json_decode($respose);
    }

    public function verifyPayment($params)
    {
        $params['merchantCode'] = $this->merchantCode;
        $params['terminalCode'] = $this->terminalCode;
        $params['timeStamp'] = date("Y/m/d H:i:s");

        $sign = $this->createSign(json_encode($params));

        $respose = $this->send($this->url . 'VerifyPayment', $params, $sign);

        return json_decode($respose);
    }
}
