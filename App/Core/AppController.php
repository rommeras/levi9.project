<?php

namespace Core;

abstract class AppController {

    protected $request;
    protected $response;
    protected $model;

    public function __construct(\Core\Request $request, \Core\Response $response, \Core\AppModel $model) {
        $this->request = $request;
        $this->response = $response;
        $this->model = $model;
    }

    abstract public function getAction();
    abstract public function postAction();
    abstract public function putAction();
}