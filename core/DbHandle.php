<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 27/05/2015
 * Time: 20:53
 */

class DbHandle {
    private $pdo;
    private $prefix;

    public function __construct($pdo, $prefix) {
        $this->pdo = $pdo;
        $this->prefix = $prefix;
    }

    public function __get($tableName) {
        return new DbTable($this->pdo, $this->prefix.$tableName);
    }

    public function pdo() {
        return $this->pdo;
    }
} 