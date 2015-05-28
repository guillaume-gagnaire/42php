<?php

/**
 * Class User
 * Represents an OMB user
 */
class                           User extends Model {
    public                      $email;
    public                      $password;
    public                      $firstname;
    public                      $lastname;
    public                      $genre;
    public                      $registered;
    public                      $admin = false;
    public                      $slug;
    public                      $lang;

    public function             beforeSave() {
        if ($this->slug == '') {
            $base = Text::slug($this->firstname).'-'.Text::slug($this->lastname);
            $suffix = '';
            $res = true;
            while (!is_null($res)) {
                $res = Db::getInstance()->User->findOne([
                    'slug' => $base . $suffix
                ]);
                if (!is_null($res))
                    $suffix = $suffix == '' ? 1 : intval($suffix) + 1;
            }
            $this->slug = $base . $suffix;
        }
    }

    public function             onSave() {
        Db::getInstance()->User->createIndex([
            'email' => 1
        ], [
            'unique' => true
        ]);
        Db::getInstance()->User->createIndex([
            'slug' => 1
        ], [
            'unique' => true
        ]);
    }

    public function             setPassword($newPassword) {
        $this->password = Hash::blowfish($newPassword);
        return $this;
    }

    public function             testPassword($password) {
        return Hash::same($password, $this->password);
    }

    public function             toJson($forSession = false) {
        $d = parent::toJson();
        unset($d['password']);
        if (!$forSession)
            unset($d['admin']);
        return $d;
    }
}

?>