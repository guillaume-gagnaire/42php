<?php

class                       Category extends Model {
    public                  $lang;
    public                  $title;
    public                  $description;
    public                  $keywords;
    public                  $slug;

    public function         beforeSave() {
        if ($this->slug == '') {
            $base = Text::slug($this->title);
            $suffix = '';
            $res = true;
            while ($res) {
                $res = Db::getInstance()->Category->findOne([
                    'slug' => $base . $suffix
                ]);
                if ($res)
                    $suffix = $suffix == '' ? 1 : intval($suffix) + 1;
            }
            $this->slug = $base . $suffix;
        }
    }
}

?>