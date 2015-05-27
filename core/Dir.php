<?php

class				Dir {
  public static function	read($path, $recursive = false, $limit = '') {
    $list = array();
    $path = realpath($path);
    if ($handle = opendir($path)) {
      while (false !== ($entry = readdir($handle)))
	if ($entry != '.' && $entry != '..') {
	  if ($limit == '' || fnmatch($limit, $entry))
	    $list[] = "$path/$entry";
	  if ($recursive && is_dir("$path/$entry"))
	    $list = array_merge($list, Dir::read("$path/$entry", true, $limit));
	}
      closedir($handle);
    }
    return $list;
  }
}

?>