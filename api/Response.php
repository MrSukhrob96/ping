<?php

namespace Api;

class Response
{

    public function __construct($code = 200, $message = array('error' => 'OK', 'response' => 'success'))
    {
       echo $this->response($code, $message);
    }


    function response($responseCode = 200, $responseMessage)
    {
        http_response_code($responseCode);
        return json_encode(
            array(
                "error" => $responseMessage['error'],
                'response' => $responseMessage['response']
            )
        );
    }
}
