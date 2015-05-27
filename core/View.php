<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 27/05/2015
 * Time: 20:36
 */

class View {
    public static function render($viewFile, $params = []) {
        if (!file_exists(ROOT.'/views/'.$viewFile.'.php'))
            return '';
        extract($params);
        ob_start();
        include ROOT.'/views/'.$viewFile.'.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
} 