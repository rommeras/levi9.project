<?php

namespace Core;

abstract class Response {

    abstract public function send($code, $data=false);
}