<?php

class                           Pagination {
    public static               $adjacent = 3;

    public static function      generate($page, $maxPage, $getParam = 'page') {
        $url = $_SERVER['REQUEST_URI'];
        $hay = strstr($url, '?', true);
        if ($hay)
            $url = $hay;
        $p = $_GET;
        if (isset($p[$getParam]))
            unset($p[$getParam]);
        if (sizeof($p)) {
            $url .= '?'.http_build_query($p).'&'.$getParam.'=';
        } else {
            $url .= '?'.$getParam.'=';
        }

        $str = '<ul class="pagination">
            <li class="arrow '.($page == 1 ? 'unavailable' : '').'"><a href="'.($page == 1 ? '' : $url.($page - 1)).'" rel="prev">&laquo;</a></li>';


        if ($maxPage < 7 + (self::$adjacent * 2)) {
            for ($counter = 1; $counter <= $maxPage; $counter++) {
                if ($counter == $page)
                    $str .= '<li class="current"><a href="">'.$counter.'</a></li>';
                else
                    $str .= '<li><a href="'.$url.$counter.'">'.$counter.'</a></li>';
            }
        } elseif ($maxPage > 5 + (self::$adjacent * 2)) {
            if ($page < 1 + (self::$adjacent * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $str .= '<li class="current"><a href="">'.$counter.'</a></li>';
                    else
                        $str .= '<li><a href="'.$url.$counter.'">'.$counter.'</a></li>';
                }
                $str .= '<li class="unavailable"><a href="">&hellip;</a></li>';
                $str .= '<li><a href="'.$url.($maxPage - 1).'">'.($maxPage - 1).'</a></li>';
                $str .= '<li><a href="'.$url.$maxPage.'">'.$maxPage.'</a></li>';
            } elseif ($maxPage - (self::$adjacent * 2) > $page && $page > (self::$adjacent * 2)) {
                $str .= '<li><a href="'.$url.'1">1</a></li>';
                $str .= '<li><a href="'.$url.'2">2</a></li>';
                $str .= '<li class="unavailable"><a href="">&hellip;</a></li>';
                for ($counter = $page - self::$adjacent; $counter <= $page + self::$adjacent; $counter++) {
                    if ($counter == $page)
                        $str .= '<li class="current"><a href="">'.$counter.'</a></li>';
                    else
                        $str .= '<li><a href="'.$url.$counter.'">'.$counter.'</a></li>';
                }
                $str .= '<li class="unavailable"><a href="">&hellip;</a></li>';
                $str .= '<li><a href="'.$url.($maxPage - 1).'">'.($maxPage - 1).'</a></li>';
                $str .= '<li><a href="'.$url.$maxPage.'">'.$maxPage.'</a></li>';
            } else {
                $str .= '<li><a href="'.$url.'1">1</a></li>';
                $str .= '<li><a href="'.$url.'2">2</a></li>';
                $str .= '<li class="unavailable"><a href="">&hellip;</a></li>';
                for ($counter = $maxPage - (2 + (self::$adjacent * 2)); $counter <= $maxPage; $counter++) {
                    if ($counter == $page)
                        $str .= '<li class="current"><a href="">'.$counter.'</a></li>';
                    else
                        $str .= '<li><a href="'.$url.$counter.'">'.$counter.'</a></li>';
                }
            }
        }

        $str .= '<li class="arrow '.($page == $maxPage ? 'unavailable' : '').'"><a href="'.($page == $maxPage ? '' : $url.($page + 1)).'" rel="next">&raquo;</a></li>
            </ul>';
        return $str;
    }
}

?>