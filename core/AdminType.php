<?php

/**
 * Class AdminType
 * List of types:
 *      text
 *      html
 *      int
 *      float
 *      price
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

    public static function      process_int($key, $value, $params, $mode) {
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
                return intval($value);
                break;
        }
    }

    public static function      process_float($key, $value, $params, $mode) {
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
                return floatval(str_replace([',', ' '], ['.', ''], $value));
                break;
        }
    }

    public static function      process_price($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return number_format(floatval($value), 2, ',', ' ');
                break;
            case 'preview':
                return number_format(floatval($value), 2, ',', ' ');
                break;
            case 'edit':
                return '<input id="field_'.$key.'" type="text" name="'.$key.'" value="'.str_replace('"', '&quot;', $value).'" />';
                break;
            case 'save':
                return floatval(str_replace([',', ' '], ['.', ''], $value));
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
                return '<textarea id="field_'.$key.'" name="'.$key.'" style="height: 350px;" class="text-left">'.$value.'</textarea>';
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
                if (!filter_var($value, FILTER_VALIDATE_EMAIL))
                    return null;
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
                return '<input id="field_'.$key.'" type="file" name="'.$key.'" style="margin-top: 9px" accept="image/*" />';
                break;
            case 'save':
                $value = Upload::job($key, false, ['jpg', 'jpeg', 'png', 'gif']);
            	if (!$value)
            		return null;
            	$i = new Image(ROOT.$value);
            	$i->exifRotation();
            	if ($params && preg_match('/^[0-9]+x[0-9]+$/', $params)) {
	            	list($width, $height) = explode('x', $params);
	            	$i->resize(intval($width), intval($height), false);
            	}
            	$i->save();
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
                return intval($value) == 1 ? '<span style="color: #43AC6A">'._t("Oui").'</span>' : '<span style="color: #F04124">'._t("Non").'</span>';
                break;
            case 'preview':
                return intval($value) == 1 ? '<span style="color: #43AC6A">'._t("Oui").'</span>' : '<span style="color: #F04124">'._t("Non").'</span>';
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
                return date(_t("d/m/Y"), strtotime($value));
                break;
            case 'preview':
                return date(_t("d/m/Y"), strtotime($value));
                break;
            case 'edit':
                Conf::append('page.bottom', '<script type="text/javascript">
                    $(function(){
                        $("#field_'.$key.'").pickadate({
                            format: "'._t("dd/mm/yyyy").'",
                            formatSubmit: "yyyy-mm-dd"
                        });
                    });
                </script>');
                return '<input id="field_'.$key.'" type="text" name="'.$key.'" value="'.str_replace('"', '&quot;', date(_t("d/m/Y"), strtotime($value))).'" />';
                break;
            case 'save':
                return $_POST[$key.'_submit'];
                break;
        }
    }

    public static function      process_datetime($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                return date(_t("d/m/Y à H:i:s"), strtotime($value));
                break;
            case 'preview':
                return date(_t("d/m/Y à H:i:s"), strtotime($value));
                break;
            case 'edit':
                Conf::append('page.bottom', '<script type="text/javascript">
                    $(function(){
                        $("#field_'.$key.'").pickadate({
                            format: "'._t("dd/mm/yyyy").'",
                            formatSubmit: "yyyy-mm-dd"
                        });
                        $("#field_'.$key.'__time").pickatime({
                            format: "'._t("HH!hi").'",
                            formatSubmit: "HH:i:00"
                        });
                    });
                </script>');
                return '<div class="row">
                            <div class="small-7 medium-8 large-9 column">
                                <input id="field_'.$key.'" type="text" name="'.$key.'" value="'.str_replace('"', '&quot;', date(_t("d/m/Y"), strtotime($value))).'" />
                            </div>
                            <div class="small-5 medium-4 large-3 column">
                                <input id="field_'.$key.'__time" type="text" name="'.$key.'__time" value="'.str_replace('"', '&quot;', date(_t("H\hi"), strtotime($value))).'" />
                            </div>
                        </div>';
                break;
            case 'save':
                $value = $_POST[$key.'_submit'] . ' ' . $_POST[$key.'__time_submit'];
                return $value;
                break;
        }
    }

    public static function      process_select($key, $value, $params, $mode) {
        switch ($mode) {
            case 'display':
                if (ArrayTools::isAssoc($params))
                    return isset($params[$value]) ? $params[$value] : '';
                return $value;
                break;
            case 'preview':
                if (ArrayTools::isAssoc($params))
                    return isset($params[$value]) ? $params[$value] : '';
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