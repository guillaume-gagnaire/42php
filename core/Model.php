<?php

class                                   Model {
    public                              $id = null;

    /**
     * Construit le document, et, si besoin, récupère les données en base
     * @param bool $id Identifiant du document
     */
    public function                     __construct($id = false, $data = null) {
        if ($id !== false) {
            if (is_null($data)) {
                $collection = get_class($this);
                $data = Db::getInstance()->$collection->findOne([
                    'id' => $id
                ]);
            }
            if (is_array($data)) {
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
        if (is_null($data['id']))
            unset($data['id']);

        foreach ($data as $k => $v) {
            if (is_bool($v))
                $data[$k] = $v ? 1 : 0;
        }

        $collection = get_class($this);
        $this->id = Db::getInstance()->$collection->save($data);
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
        if (isset($data['id']))
            unset($data['id']);
        $newOne = new $collection();
        foreach ($data as $k => $v)
            $newOne->$k = $v;
        return $newOne;
    }

    /**
     * Supprime un document
     */
    public function                     delete() {
        if (!is_null($this->id)) {
            $collection = get_class($this);
            Db::getInstance()->$collection->remove([
                'id' => $this->id
            ]);
            $this->id = null;
        }
    }

    /**
     * Retourne les données de l'objet pour un export vers JSON
     * @return array
     */
    public function                     toJson() {
        $data = $this->__data();
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
            $ret[] = new $collection($line['id'], $line);
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
        if (!$data)
            return false;
        return new $collection($data['id'], $data);
    }
}

?>