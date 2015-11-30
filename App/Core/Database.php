<?php

namespace Core;

require_once(__DIR__.'/../../database_config.php');

class Database {

    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Database Handler
    private $stmt;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ];
        try {
            $this->dbh = new \PDO($dsn, $this->user, $this->pass, $options);
        } catch(\PDOException $e){
            $this->error = $e->getMessage();
            // Log error
            $this->displayInternalError();
        }
    }

    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function getResult() {
        $this->execute();
        try {
            return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->displayInternalError();
        }
    }

    public function getSingleResult(){
        $this->execute();
        try {
            return $this->stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->displayInternalError();
        }
    }

    public function execute(){
        try {
            $this->stmt->execute();
        } catch (\PDOException $e) {
            $this->displayInternalError();
        }
    }

    public function setParameter($param, $value) {
        switch (true) {
            case is_int($value):
                $type = \PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = \PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = \PDO::PARAM_NULL;
                break;
            default:
                $type = \PDO::PARAM_STR;
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function setParameters(array $params) {
        foreach ($params as $name => $value) {
            $this->setParameter($name, $value);
        }
    }

    protected function displayInternalError(){
        (new \Core\JsonResponse())->sendError(500, \Core\JsonResponse::DB_ERROR);
    }

}