<?php

class                   AdminTable {
    public              $params = [];

    public function     __construct($params = []) {
        $this->params = $params;
    }

    private function 	auto() {
	    $mode = 'list';
        $item_id = 0;
        if (isset($_GET['id'])) {
            $mode = 'edit';
            $item_id = intval($_GET['id']);
            if (sizeof($_POST)) {
                $mode = 'save';
            }
            if (isset($_GET['delete'])) {
                $mode = 'delete';
            }
        }
        $methodName = 'auto_'.$mode;
        return $this->$methodName($item_id);
    }

    private function    getType($colname) {
        $type = isset($this->params['fields'][$colname]) ? $this->params['fields'][$colname]['type'] : 'text';
        $params = false;
        if (is_array($type)) {
            $params = isset($type[1]) ? $type[1] : false;
            $type = $type[0];
        }
        return [$type, $params];
    }

    private function    auto_list($id) {
        $page = 1;
        if (isset($_GET['page']))
            $page = intval($_GET['page']);
        $limit = 10;
        if (isset($this->params['limit']))
            $limit = intval($this->params['limit']);

        $table = $this->params['table'];

        $totalEls = Db::get('SELECT COUNT(id) as nb FROM `'.$table.'`');
        $maxPage = ceil(intval($totalEls['nb']) / $limit);
        if ($page > $maxPage)
            $page = $maxPage;

        $items = Db::query('SELECT * FROM `'.$table.'` ORDER BY id DESC LIMIT '.(($page - 1) * $limit).', '.$limit);
        $cols = explode('|', $this->params['header']);
        $src = '<table>
            <thead>
                <th>ID</th>';
        foreach ($cols as $fieldname) {
            $src .= '<th>'.(isset($this->params['fields'][$fieldname]) ? $this->params['fields'][$fieldname]['title'] : ucfirst($fieldname)).'</th>';
        }
        $src .= '<th></th></thead><tbody>';
        foreach ($items as $item) {
            $src .= '<tr><td>'.$item['id'].'</td>';
            foreach ($cols as $colname) {
                list($type, $params) = $this->getType($colname);
                $type = "process_$type";
                $src .= '<td>' . AdminType::$type($colname, isset($item[$colname]) ? $item[$colname] : '', $params, 'preview') . '</td>';
            }
            $src .= '<td class="admin-icons">
                        <a href="'.Conf::get('admin.url').'&id='.$item['id'].'"><i class="fi-pencil"></i></a>
                        <a href="'.Conf::get('admin.url').'&id='.$item['id'].'&delete"><i class="fi-trash"></i></a>
                    </td>
                </tr>';
        }
        if (!sizeof($items))
            $src .= '<tr><td colspan="'.(2 + sizeof($cols)).'" class="text-center">'._t("Aucun élément.").'</td></tr>';
        $src .= '</tbody></table>';


        return View::partial('admin/list', [
            'pagination' => Pagination::generate($page, $maxPage),
            'items' => $src,
            'title' => $this->params['title'],
            'item_label' => $this->params['item']
        ]);
    }

    private function    auto_edit($id) {

    }

    private function    auto_save($id) {

    }

    private function    auto_delete($id) {

    }

    private function 	standalone() {
	    if (!isset($this->params['handler']))
	    	return _t("Aucune fonction.");
	    return $this->params['handler']();
    }
    
    public function 	render() {
	    $methodName = $this->params['mode'];
	    if (!method_exists($this, $methodName))
	    	return _t("Ce mode n'existe pas.");
	    return $this->$methodName();
    }
}

?>