<?php

class                   PagesController extends Controller {
    public function     process($p) {
        $page = Page::findOne([
            'path' => $p->path
        ]);
        if (!$page)
            Http::throw404(true);

        if ($page->title != '')
            Conf::set('page.title', $page->title);
        if ($page->description != '')
            Conf::set('page.description', $page->description);
        if ($page->keywords != '')
            Conf::set('page.keywords', $page->keywords);
        if ($page->image != '')
            Conf::set('page.share.image', $page->image);

        return View::render($page->file);
    }
}

?>