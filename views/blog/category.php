<?= View::partial('header') ?>

<style type="text/css">
    .post_photo {
        display: block;
        min-height: 220px;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .post_title {
        color: #363636;
        font-size: 24px;
    }

    .post_intro {
        height: 8em;
        overflow: hidden;
    }

    .post_links {
        margin-top: 5px;
    }

    .post_links a.button {
        margin: 0;
    }

    .post_preview {
        padding-bottom: 2em;
    }

    .pagination-centered {
        margin-top: 40px;
    }
</style>

<div class="row">
    <div class="small-12 column">
        <ul class="breadcrumbs">
            <li><a href="/"><?= _t("Accueil") ?></a></li>
            <li<?= $category === false ? ' class="current"' : '' ?>><a href="<?= Argv::createUrl('blogCategory', ['p1' => '']) ?>"><?= _t("Blog") ?></a></li>
            <?php if ($category !== false) { ?>
                <li class="current"><a href="<?= Argv::createUrl('blogCategory', ['p1' => $category->slug]) ?>"><?= $category->title ?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>

<div class="row">
    <div class="small-12 column">
        <h1><?= $category === false ? _t("Blog") : $category->title ?></h1>
    </div>
</div>

<div class="row">
    <div class="small-12 column">
        <?php foreach ($posts as $post) { ?>
            <div class="row post_preview" id="post_<?= $post->id ?>" data-equalizer>
                <div class="small-12 medium-5 large-4 column">
                    <a href="<?= $post->getUrl() ?>" class="post_photo" style="background-image:url(<?= $post->image ?>)" data-equalizer-watch></a>
                </div>
                <div class="small-12 medium-7 large-8 column" data-equalizer-watch>
                    <a href="<?= $post->getUrl() ?>" class="post_title"><?= $post->title ?></a>
                    <div class="post_intro"><?= $post->intro ?></div>
                    <div class="text-right small-only-text-center post_links">
                        <a href="<?= $post->getUrl() ?>" class="button small"><?=_t("Lire la suite") ?></a>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (!sizeof($posts)) { ?>
            <div class="row">
                <div class="small-12 column">
                    <div class="panel text-center">
                        <?= _t("Il n'y a aucun article dans cette catégorie.") ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="small-12 column">
        <div class="pagination-centered">
            <?=Pagination::generate($page, $maxpage) ?>
        </div>
    </div>
</div>

<?= View::partial('footer') ?>