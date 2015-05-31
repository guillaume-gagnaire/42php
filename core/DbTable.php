<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 27/05/2015
 * Time: 20:54
 */

class DbTable {
    private $pdo;
    private $tableName;

    public function __construct($pdo, $tableName) {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
    }

    public function insert($data) {
        $keys = [];
        $values = [];
        foreach ($data as $k => $v) {
            $keys[] = '`'.$k.'`';
            $values[] = $this->pdo->quote($v);
        }
        $query = 'INSERT INTO `'.$this->tableName.'` ('.implode(', ', $keys).') VALUES ('.implode(', ', $values).')';
        $this->pdo->exec($query);
        return $this->pdo->lastInsertId();
    }

    public function update($clause = [], $data = []) {
        if (!sizeof($clause) && !sizeof($data))
            return;
        $d = [];
        foreach ($data as $k => $v)
            $d[] = '`'.$k.'`='.$this->pdo->quote($v);
        $query = 'UPDATE `'.$this->tableName.'` SET '.implode(', ', $d);
        if (sizeof($clause))
            $query .= ' WHERE '.Db::where($clause);
        return $this->pdo->exec($query);
    }

    public function save($data) {
        if (isset($data['id'])) {
            $d = $data;
            unset($d['id']);
            $this->update([
                'id' => $data['id']
            ], $d);
            return $data['id'];
        } else
            return $this->insert($data);
    }

    public function find($clause = [], $values = [], $order = [], $limit = false) {
        $query = 'SELECT ';
        if (sizeof($values))
            $query .= '`'.implode('`, `', $values).'`';
        else
            $query .= '*';
        $query .= ' FROM `'.$this->tableName.'`';
        if (sizeof($clause))
            $query .= ' WHERE '.Db::where($clause);
        if (sizeof($order))
            $query .= ' ORDER BY '.Db::order($order);
        if ($limit)
            $query .= ' LIMIT '.$limit;
        $req = $this->pdo->query($query);
        return $req->fetchAll();
    }

    public function findOne($clause = [], $values = [], $order = []) {
        $query = 'SELECT ';
        if (sizeof($values))
            $query .= '`'.implode('`, `', $values).'`';
        else
            $query .= '*';
        $query .= ' FROM `'.$this->tableName.'`';
        if (sizeof($clause))
            $query .= ' WHERE '.Db::where($clause);
        if (sizeof($order))
            $query .= ' ORDER BY '.Db::order($order);
        $query .= ' LIMIT 1';
        $req = $this->pdo->query($query);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function remove($clause = []) {
        $query = 'DELETE FROM `'.$this->tableName.'`';
        if (sizeof($query))
            $query .= ' WHERE '.Db::where($clause);
        return $this->pdo->exec($query);
    }
}