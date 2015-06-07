<?= View::partial('header') ?>

<style type="text/css">
    .post_photo {
        display: block;
        height: 500px;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
    }

    .post_title {
        color: #363636;
        font-size: 24px;
    }

    .post_intro {
        font-weight: 700;
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

    .post_content {
        padding: 20px;
        background: #fff;
        position: relative;
        top: -133px;
    }
</style>

<div class="post_photo" style="background-image:url(<?= $article->image ?>)"></div>

<div class="row">
    <div class="small-12 column">
        <div class="post_content">
            <div class="row">
                <div class="small-12 column">
                    <ul class="breadcrumbs">
                        <li><a href="/"><?= _t("Accueil") ?></a></li>
                        <li><a href="<?= Argv::createUrl('blogCategory', ['p1' => '']) ?>"><?= _t("Blog") ?></a></li>
                        <li><a href="<?= Argv::createUrl('blogCategory', ['p1' => $category->slug]) ?>"><?= $category->title ?></a></li>
                        <li class="current"><a href="<?= $article->getUrl() ?>"><?= $article->title ?></a></li>
                    </ul>
                </div>
            </div>


            <h1><?= $article->title ?></h1>
            <div class="post_intro">
                <?= $article->intro ?>
            </div>
            <div class="post_text">
                <?= $article->content ?>
            </div>


        </div>
    </div>
</div>

<?= View::partial('footer') ?>