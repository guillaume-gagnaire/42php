<?php

if (isset($_GET['setLang']) && in_array($_GET['setLang'], i18n::$__acceptedLanguages)) {
    i18n::setLang($_GET['setLang']);

    unset($_GET['setLang']);

    if (Conf::get('route') !== false) {
        $url = Argv::createUrl(Conf::get('route.name'), Conf::get('route.params'));
        if (sizeof($_GET))
            $url .= '?'.http_build_query($_GET);
        Redirect::http($url);
    }
}

?>