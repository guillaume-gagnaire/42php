<?php

class                                   Model {
    public                              $_id = null;

    /**
     * Construit le document, et, si besoin, récupère les données en base
     * @param bool $id Identifiant du document
     */
    public function                     __construct($id = false, $data = null) {
        if ($id !== false) {
            if (is_null($data)) {
                $collection = get_class($this);
                $data = Db::getInstance()->$collection->findOne([
                    '_id' => new MongoId($id)
                ]);
            }
            if (!is_null($data)) {
                foreach ($data as $k => $v) {
                    if (property_exists($this, $k))
                        $this->$k = $v;
                }
            }
        }
    }

    /**
     * Récupère les données du document
     * @return array
     */
    public function                     __data() {
        $data = [];
        foreach ($this as $k => $v) {
            if (property_exists($this, $k))
                $data[$k] = $v;
        }
        return $data;
    }

    /**
     * Sauvegarde le document
     */
    public function                     save() {
        if (method_exists($this, 'beforeSave'))
            $this->beforeSave();
        $data = $this->__data();
        if (is_null($data['_id']))
            unset($data['_id']);
        $collection = get_class($this);
        Db::getInstance()->$collection->save($data);
        $this->_id = $data['_id'];
        if (method_exists($this, 'onSave'))
            $this->onSave();
    }

    /**
     * Duplique le document
     * @return mixed
     */
    public function                     duplicate() {
        $collection = get_class($this);
        $data = $this->__data();
        if (is_null($data['_id']))
            unset($data['_id']);
        $newOne = new $collection();
        foreach ($data as $k => $v)
            $newOne->$k = $v;
        return $newOne;
    }

    /**
     * Supprime un document
     */
    public function                     delete() {
        if (!is_null($this->_id)) {
            $collection = get_class($this);
            Db::getInstance()->$collection->remove([
                '_id' => $this->_id
            ]);
            $this->_id = null;
        }
    }

    /**
     * Retourne les données de l'objet pour un export vers JSON
     * @return array
     */
    public function                     toJson() {
        $data = $this->__data();
        $data['id'] = $data['_id'];
        unset($data['_id']);
        return $data;
    }

    /**
     * Cherche dans la base un ensemble de documents
     * @param $query Requête mongo
     * @return array Un jeu de documents
     */
    public static function              find($query = []) {
        $collection = get_called_class();
        $data = Db::getInstance()->$collection->find($query);
        $ret = [];
        foreach ($data as $line)
            $ret[] = new $collection((string)$line['_id'], $line);
        return $ret;
    }

    /**
     * Cherche dans la base un document
     * @param $query Requête mongo
     * @return mixed Le document, ou false
     */
    public static function              findOne($query = []) {
        $collection = get_called_class();
        $data = Db::getInstance()->$collection->findOne($query);
        if (is_null($data))
            return false;
        return new $collection((string)$data['_id'], $data);
    }
}

?>