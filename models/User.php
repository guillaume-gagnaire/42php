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
    public                      $photo;

    public function             beforeSave() {
        if ($this->slug == '') {
            $base = Text::slug($this->firstname).'-'.Text::slug($this->lastname);
            $suffix = '';
            $res = true;
            while ($res) {
                $res = Db::getInstance()->User->findOne([
                    'slug' => $base . $suffix
                ]);
                if ($res)
                    $suffix = $suffix == '' ? 1 : intval($suffix) + 1;
            }
            $this->slug = $base . $suffix;
        }
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