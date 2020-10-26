<?php

class Respuestas
{
    private $response = [
        'status' => 'ok',
        'result' => []
    ];

    public function error_405(){
        $this->response['status'] = 'error';
        $this->response['result'] = [
            'error_id' => '405',
            'error_msg' => 'Metodo no permitido'
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
    }
}
