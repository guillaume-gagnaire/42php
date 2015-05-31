<?php

class                           Article extends Model {
    public                      $title;
    public                      $intro;
    public                      $content;
    public                      $lang;
    public                      $category;
    public                      $keywords;
    public                      $date;
    public                      $image;
    public                      $author;
    public                      $enabled;
    public                      $slug;

    public function             beforeSave() {
        if ($this->slug == '') {
            $base = Text::slug($this->title);
            $suffix = '';
            $res = true;
            while ($res) {
                $res = Db::getInstance()->Article->findOne([
                    'slug' => $base . $suffix
                ]);
                if ($res)
                    $suffix = $suffix == '' ? 1 : intval($suffix) + 1;
            }
            $this->slug = $base . $suffix;
        }
    }

    public static function      getMaxPage($limit = 10, $category = false) {
        $ret = Db::get('SELECT COUNT(`id`) as `nb` FROM `Article` WHERE `enabled`=1 '.($category !== false ? 'AND `category`='.Db::quote($category) : ''));
        return ceil(intval($ret['nb']) / $limit);
    }

    public static function      getPosts($page = 1, $limit = 10, $category = false) {
        $ret = [];
        $posts = Db::query('SELECT * FROM `Article` WHERE `enabled`=1 '.($category !== false ? 'AND `category`='.Db::quote($category) : '').' ORDER BY `id` DESC LIMIT '.(($page - 1) * $limit).', '.$limit);
        foreach ($posts as $post)
            $ret[] = new Article($post['id'], $post);
        return $ret;
    }
}

?>