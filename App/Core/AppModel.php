<?php

namespace Core;

abstract class AppModel {

    protected $db;
    protected $fillable = [];

    public function __construct(\Core\Database $db) {
        $this->db = $db;
    }

    protected function filterData($data) {
        $fillable = array_map('strtoupper', $this->fillable);
        
        return array_filter($data, function ($name) use ($fillable) {
            return in_array(strtoupper($name), $fillable, true);
        }, ARRAY_FILTER_USE_KEY);

    }

}