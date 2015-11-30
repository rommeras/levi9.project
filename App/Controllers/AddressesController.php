<?php

namespace Controllers;
use \Core\AppController;

class AddressesController extends AppController {

    public function getAction() {

        if ($this->request->resourceId !== null) {
            $this->getOne($this->request->resourceId);
        } else {
            $this->getCollection();
        }
    }

    public function postAction() {

        if ($this->request->resourceId !== null)
            $this->response->send(400);
        if ( !in_array($this->request->format, \Core\Configuration::$allowedRequestFormats, true) || $this->request->data === null )
        $this->response->sendError(400, \Core\JsonResponse::CONTENT_TYPE_ERROR);

        $result = $this->model->create($this->request->data);
        if ($result)
            $this->response->send(200);
        else
            $this->response->sendError(400, \Core\JsonResponse::DATA_ERROR);
    }

    public function putAction() {

        if ($this->request->resourceId === null)
            $this->response->sendError(400, \Core\JsonResponse::PUT_COLLECTION_ERROR);
        if ( !in_array($this->request->format, \Core\Configuration::$allowedRequestFormats, true) || $this->request->data === null)
            $this->response->sendError(400, \Core\JsonResponse::CONTENT_TYPE_ERROR);

        $address = $this->model->findOne($this->request->resourceId);
        if ($address) {
            $result = $this->model->update($this->request->resourceId, $this->request->data);
            if ($result)
                $this->response->send(200);
            else
                $this->response->sendError(400, \Core\JsonResponse::DATA_ERROR);
        } else {
            $this->response->send(404);
        }
    }

    protected function getOne($id) {
        $address = $this->model->findOne($id);
        if ($address)
            $this->response->send(200, $address);
        else
            $this->response->send(404);
    }

    protected function getCollection() {
        $addresses = $this->model->findAll();
        $this->response->send(200, $addresses);
    }
}