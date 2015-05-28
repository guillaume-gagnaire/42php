<!DOCTYPE html>
<html lang="<?= Conf::get('lang') ?>">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= Conf::get('page.title') ?></title>
        <meta name="description" content="<?= str_replace('"', '&quot;', Conf::get('page.description')) ?>" />
        <meta name="keywords" content="<?= str_replace('"', '&quot;', Conf::get('page.keywords')) ?>" />

        <!-- Facebook share -->
        <meta property="og:title" content="<?= str_replace('"', '&quot;', Conf::get('page.title')) ?>">
        <meta property="og:image" content="<?= str_replace('"', '&quot;', Conf::get('page.share.image')) ?>">
        <meta property="og:description" content="<?= str_replace('"', '&quot;', Conf::get('page.description')) ?>">

        <!-- Twitter share -->
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="<?= Conf::get('page.share.twitter.username') ?>" />
        <meta name="twitter:title" content="<?= str_replace('"', '&quot;', Conf::get('page.title')) ?>" />
        <meta name="twitter:description" content="<?= str_replace('"', '&quot;', Conf::get('page.description')) ?>" />
        <meta name="twitter:image" content="<?= str_replace('"', '&quot;', Conf::get('page.share.image')) ?>" />

        <?php
        // alternate links for multilingual
        if (Conf::get('route') !== false) {
            foreach (i18n::$__acceptedLanguages as $l) {
                $url = Argv::createUrl(Conf::get('route.name'), Conf::get('route.params'), $l);
                echo '<link rel="alternate" hreflang="'.$l.'" href="http://'.$_SERVER['HTTP_HOST'].$url.'" />'."\n";
            }
        }
        ?>

        <!-- Foundation pre-loads -->
        <link rel="stylesheet" href="/lib/foundation/css/foundation.css" />
        <script src="/lib/foundation/js/vendor/modernizr.js"></script>

        <!-- HTML5 shiv -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    </head>
    <body>