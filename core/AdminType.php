<?php

/**
 * Class AdminType
 * List of types:
 *      text
 *      html
 *      password
 *      image
 *      file
 *      email
 *      date
 *      datetime
 *      bool
 *      select
 *		hidden
 */
class                           AdminType {
    public static function      process_hidden($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return $value;
                break;
            case 'preview':
                return $value;
                break;
            case 'edit':
                return '<input type="hidden" name="'.$key.'" value="'.str_replace('"', '&quot;', $value).'" />';
                break;
            case 'save':
                return $value;
                break;
        }
    }

    public static function      process_text($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return $value;
                break;
            case 'preview':
                return $value;
                break;
            case 'edit':
                return '<input id="field_'.$key.'" type="text" name="'.$key.'" value="'.str_replace('"', '&quot;', $value).'" />';
                break;
            case 'save':
                return $value;
                break;
        }
    }


    public static function      process_html($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return $value;
                break;
            case 'preview':
                return $value;
                break;
            case 'edit':
                Conf::append('page.bottom', '<script type="text/javascript">
                    $(function(){
                        $("#field_'.$key.'").redactor({
                            imageUpload: "'.Argv::createUrl('admin').'?module=wysiwygImageUpload",
                            plugins: ["table", "fontsize", "fullscreen", "video"],
                            minHeight: 300,
                            maxHeight: 800,
                            lang: "'.Conf::get('lang').'"
                        });
                    });
                </script>');
                return '<textarea id="field_'.$key.'" name="'.$key.'" style="height: 350px;">'.$value.'</textarea>';
                break;
            case 'save':
                return $value;
                break;
        }
    }

    public static function      process_password($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return '******';
                break;
            case 'preview':
                return '******';
                break;
            case 'edit':
                return '<input id="field_'.$key.'" type="password" name="'.$key.'" value="" autocomplete="off" placeholder="'._t("Laissez vide pour ne pas changer de mot de passe").'" />
                        <input type="password" name="'.$key.'_confirm" value="" autocomplete="off" placeholder="'._t("Confirmez").'" />';
                break;
            case 'save':
                if (trim($value) == '')
                    return null;
                if ($value != $_POST[$key.'_confirm'])
                    return null;
                return Hash::blowfish($value);
                break;
        }
    }

    public static function      process_email($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return $value;
                break;
            case 'preview':
                return $value;
                break;
            case 'edit':
                return '<input id="field_'.$key.'" type="email" name="'.$key.'" value="'.str_replace('"', '&quot;', $value).'" />';
                break;
            case 'save':
                return $value;
                break;
        }
    }

    public static function      process_image($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                if (!strlen($value))
                    return '';
                return '<img src="'.$value.'" alt="" />';
                break;
            case 'preview':
                if (!strlen($value))
                    return '';
                return '<img src="'.$value.'" style="max-height: 32px; max-width: 32px;" alt="" />';
                break;
            case 'edit':
                return '<input id="field_'.$key.'" type="file" name="'.$key.'" style="margin-top: 9px" />';
                break;
            case 'save':
                $value = Upload::job($key, false, ['jpg', 'jpeg', 'png', 'gif']);
            	if (!$value)
            		return null;
            	if ($params && preg_match('/^[0-9]+x[0-9]+$/', $params)) {
	            	list($width, $height) = explode('x', $params);
	            	$i = new Image(ROOT.$value);
	            	$i->resize(intval($width), intval($height), false);
	            	$i->save();
            	}
                return $value;
                break;
        }
    }

    public static function      process_file($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return '<a href="'.$value.'" target="_blank">'._t("Accéder au fichier").'</a>';
                break;
            case 'preview':
                return '<a href="'.$value.'" target="_blank">'._t("Accéder au fichier").'</a>';
                break;
            case 'edit':
                return '<input id="field_'.$key.'" type="file" name="'.$key.'" style="margin-top: 9px" />';
                break;
            case 'save':
            	$value = Upload::job($key);
            	if (!$value)
            		return null;
                return $value;
                break;
        }
    }

    public static function      process_bool($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return intval($value) == 1 ? _t("Oui") : _t("Non");
                break;
            case 'preview':
                return intval($value) == 1 ? _t("Oui") : _t("Non");
                break;
            case 'edit':
                return '<input id="field_'.$key.'" type="checkbox" name="'.$key.'" value="1"'.(intval($value) == 1 ? ' checked="checked"' : '').' style="margin-top: 14px" />';
                break;
            case 'save':
                return isset($_POST[$key]) ? 1 : 0;
                break;
        }
    }

    public static function      process_date($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return $value;
                break;
            case 'preview':
                return $value;
                break;
            case 'edit':
                return '<input id="field_'.$key.'" type="text" name="'.$key.'" value="'.str_replace('"', '&quot;', $value).'" />';
                break;
            case 'save':
                return $value;
                break;
        }
    }

    public static function      process_datetime($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return $value;
                break;
            case 'preview':
                return $value;
                break;
            case 'edit':
                return '<input id="field_'.$key.'" type="text" name="'.$key.'" value="'.str_replace('"', '&quot;', $value).'" />';
                break;
            case 'save':
                return $value;
                break;
        }
    }

    public static function      process_select($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return $value;
                break;
            case 'preview':
                return $value;
                break;
            case 'edit':
                $str = '<select id="field_'.$key.'" name="'.$key.'">';
                foreach ($params as $k => $v) {
                    if (!ArrayTools::isAssoc($params))
                        $k = $v;
                    $str .= '<option value="'.str_replace('"', '&quot;', $k).'"'.($value == $v ? ' selected="selected"' : '').'>'.$v.'</option>';
                }
                $str .= '</select>';
                return $str;
                break;
            case 'save':
                return $value;
                break;
        }
    }
}

?>