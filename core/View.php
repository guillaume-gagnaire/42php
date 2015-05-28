<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 27/05/2015
 * Time: 20:36
 */

class View {
    public static function renderFile($viewFile, $params, $header) {
        if (!file_exists(ROOT.'/views/'.$viewFile.'.php'))
            return '';
        extract($params);
        ob_start();
        if ($header && file_exists(ROOT.'/views/htmlheader.php'))
            include ROOT.'/views/htmlheader.php';
        include ROOT.'/views/'.$viewFile.'.php';
        if ($header && file_exists(ROOT.'/views/htmlfooter.php'))
            include ROOT.'/views/htmlfooter.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public static function render($viewFile, $params = []) {
        return self::renderFile($viewFile, $params, true);
    }

    public static function partial($viewFile, $params = []) {
        return self::renderFile($viewFile, $params, false);
    }

    public static function slick() {
        return '<link rel="stylesheet" type="text/css" href="/lib/slick/slick.css" />
                <script type="text/javascript" src="/lib/slick/slick.min.js"></script>';
    }

    public static function angular() {
        return '<script type="text/javascript" src="/lib/angular/angular.min.js"></script>';
    }

    public static function redactor($selector = 'textarea', $libs = [], $opts = []) {
        if (sizeof($libs))
            $libs['plugins'] = $opts;
        $src = '<link rel="stylesheet" type="text/css" href="/lib/redactor/redactor.css" />
                <script type="text/javascript" src="/lib/redactor/redactor.js"></script>';
        foreach ($libs as $lib)
            $src .= '<script type="text/javascript" src="/lib/redactor/'.$lib.'.js"></script>';
        $src .= '<script type="text/javascript">
                    $(function(){
                        $("'.$selector.'").redactor('.json_encode($opts).');
                    });
                </script>';
        return $src;
    }
} 