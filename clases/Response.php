<?php

class Response
{
    private $response = [
        'status' => 'ok',
        'result' => []
    ];

    /* public function error_405(){
        $this->response['status'] = 'error';
        $this->response['result'] = [
            'error_id' => '405',
            'error_msg' => 'Metodo no permitido'
        ];

        return $this->response;
    }

    public function error_200($text = "Error"){
        $this->response['status'] = 'error';
        $this->response['result'] = [
            'error_id' => '200',
            'error_msg' => $text
        ];

        return $this->response;
    }

    public function error_400(){
        $this->response['status'] = 'error';
        $this->response['result'] = [
            'error_id' => '400',
            'error_msg' => 'Petición errónea'
        ];

        return $this->response;
    } */

    public function sendResponse($code , $msg = null){
        $this->response['status'] = $code == 200 ? 'Ok' : 'Error';
        $this->response['result'] = [
            'error_id' => $code,
            'error_msg' => $msg ?? $this->_getStatusCodeMessage($code)
        ];

        return $this->response;
    }

    private function _getStatusCodeMessage($status)
    {
        
        $codes = [
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method no allowed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        ];
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}
