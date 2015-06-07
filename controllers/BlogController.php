<?php

class                       BlogController extends Controller {
    public function         process($p) {
        $article = false;
        $category = false;
        if ($p->p1 != '') {
            $category = $p->p1;
        }
        if ($p->p2 != '') {
            $article = $p->p2;
        }

        if ($article !== false)
            return $this->article($article, $category);
        if ($category !== false)
            return $this->category($category);
        return $this->category(false);
    }

    public function         processCategory($p) {
        return $this->process((object)[
            'p1' => $p->p1,
            'p2' => ''
        ]);
    }

    public function         article($articleid, $categoryid) {
        $article = Article::findOne([
            'slug' => $articleid
        ]);
        if (!$article)
            Http::throw404(true);
        $category = $article->getCategory();
        if ($category->slug != $categoryid)
            Redirect::permanent($article->getUrl());

        Conf::set('page.title', $article->title . ' - ' . Conf::get('page.title'));
        Conf::set('page.description', strip_tags($article->intro));
        Conf::set('page.keywords', $article->keywords);
        Conf::set('page.share.image', $article->image);

        return View::render('blog/article', [
            'article' => $article,
            'category' => $category
        ]);
    }

    public function         category($categoryid) {
        $cat = false;
        $category = false;
        if ($categoryid !== false) {
            $category = Category::findOne([
                'slug' => $categoryid
            ]);
            if (!$category)
                Http::throw404(true);
            $cat = $category->id;

            Conf::set('page.title', $category->title . ' - ' . Conf::get('page.title'));
            Conf::set('page.description', $category->description);
            Conf::set('page.keywords', $category->keywords);
        }
        $limit = 10;
        $page = 1;
        if (isset($_GET['page']))
            $page = intval($_GET['page']);
        $maxpage = Article::getMaxPage($limit, $cat);

        if ($page < 1)
            $page = 1;
        if ($page > $maxpage)
            $page = $maxpage;

        $posts = Article::getPosts($page, $limit, $cat);
        return View::render('blog/category', [
            'page' => $page,
            'maxpage' => $maxpage,
            'posts' => $posts,
            'category' => $category
        ]);
    }
}

?>