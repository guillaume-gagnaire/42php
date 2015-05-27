<?php

class                           Log {
    public static function      create($service, $data = []) {
        $data['service'] = $service;
        $data['date'] = date('Y-m-d H:i:s');
        $data['ip'] = IP::get();
        Db::getInstance()->Logs->insert($data);
    }
}

?>