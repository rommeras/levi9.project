<?php

namespace Core;

class Request {

    public $method;
    public $format;
    public $resourceName;
    public $urlParts;
    public $data;

    public function __construct() {

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->urlParts = isset($_SERVER['PATH_INFO']) ? explode('/', $_SERVER['PATH_INFO']) : [];
        $this->resourceName = !empty($this->urlParts[1]) ? $this->urlParts[1] : '';
        if (!empty($this->urlParts[2])) {
            $this->resourceId = is_numeric($this->urlParts[2])? (int) $this->urlParts[2] : 0;
        } else {
            $this->resourceId = null;
        }
        $this->format = isset($_SERVER['CONTENT_TYPE'])? $_SERVER['CONTENT_TYPE'] : '';
        $this->data = $this->getData();
    }

    public function getData() {

        $body = file_get_contents("php://input");
        $data = $this->format == "application/json" ? json_decode($body, true) : null;

        return $data;
    }
}