<?php


namespace Core;

class JsonResponse extends Response {

    const CONTENT_TYPE_ERROR = 'Request content-type must be json';
    const DATA_ERROR = 'Data is not valid';
    const DB_ERROR = 'Database error';
    const PUT_COLLECTION_ERROR = 'PUT method on collection is not allowed';
    const URL_VALIDATION_ERROR = 'URL pattern must be http://{resource}/{id}/';

    protected $status = [
        200 => 'OK',
        400 => 'Bad Request',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    ];

    public function send($code, $data=false) {

        if (isset($this->status[$code])) {
            header("HTTP/1.1 " . $code . " " . $this->status[$code]);
            header('Content-Type: application/json');
            if ($data !== false)
                echo json_encode($data);
        }
        die;
    }

    public function sendError($code, $message) {
        $this->send($code, ['errorMessage' => $message]);
    }

}