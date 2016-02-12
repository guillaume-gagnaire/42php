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
        $order = 'id DESC';
        if (isset($this->params['sortable'])) {
        	$limit = 99999;
        	$page = 1;
        	$order = '`'.$this->params['sortable'].'` ASC';
		}

        $table = $this->params['table'];
		
		$restrict = [];
		if (isset($this->params['restrict']))
			$restrict = $this->params['restrict'];
		
		$where = '';
		if (sizeof($restrict)) {
			$where = [];
			foreach ($restrict as $k => $v)
				$where[] = '`'.$k.'`='.Db::quote($v);
			$where = 'WHERE '.implode(' AND ', $where);
		}
		
        $totalEls = Db::get('SELECT COUNT(id) as nb FROM `'.$table.'` '.$where);
        $maxPage = ceil(intval($totalEls['nb']) / $limit);
        if (!$maxPage)
            $maxPage = 1;
        if ($page > $maxPage)
            $page = $maxPage;

        $items = Db::query('SELECT * FROM `'.$table.'` '.$where.' ORDER BY '.$order.' LIMIT '.(($page - 1) * $limit).', '.$limit);
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

        
        $sortable = false;
        $sortable_table = false;
        if (isset($this->params['sortable'])) {
	        $sortable = $this->params['sortable'];
	        $sortable_table = $table;
        }

        return View::partial('admin/list', [
            'pagination' => Pagination::generate($page, $maxPage),
            'items' => $src,
            'title' => $this->params['title'],
            'item_label' => $this->params['item'],
            'fields' => $this->params['fields'],
            'filter' => isset($this->params['filter']) ? $this->params['filter'] : false,
            'sortable' => $sortable,
            'sortable_table' => $sortable_table
        ]);
    }

    private function    auto_edit($id, $unique_alerts = []) {
		$values = [];
		foreach ($this->params['fields'] as $k => $v) {
			$values[$k] = isset($v['default']) ? $v['default'] : '';
		}
		
		if ($id) {
			$data = Db::get('SELECT * FROM `'.$this->params['table'].'` WHERE `id`='.$id);
			if ($data) {
				foreach ($values as $k => $v)
					$values[$k] = $data[$k];
			}
		}
		
		$editing = $values;
		foreach ($values as $k => $v) {
			if (isset($_POST[$k]))
				$editing[$k] = $_POST[$k];
		}
		
		$types = [];
		foreach ($this->params['fields'] as $k => $v) {
			$types[$k] = $this->getType($k);
		}
		
		$titles = [];
		foreach ($this->params['fields'] as $k => $v) {
			$titles[$k] = isset($v['title']) ? $v['title'] : ucfirst($k);
		}

        $status = '';
        if (isset($_GET['status']))
            $status = $_GET['status'];

		return View::partial('admin/edit', [
			'id' => $id,
            'itemtitle' => $this->params['item'],
			'types' => $types,
			'values' => $values,
			'editing' => $editing,
			'titles' => $titles,
            'status' => $status,
            'unique' => $unique_alerts
		]);
    }

    private function    auto_save($id) {
        $set = [];
        $unique_alert = [];
        foreach ($this->params['fields'] as $k => $v) {
            list($type, $params) = $this->getType($k);
            $method = "process_$type";
            $value = AdminType::$method($k, isset($_POST[$k]) ? $_POST[$k] : '', $params, 'save');

            if (!is_null($value)) {
                if (trim($value) == '' && isset($this->params['fields'][$k]['ifEmpty']))
                    $value = $this->params['fields'][$k]['ifEmpty']();

                // Check unique
                if ($value != '' && isset($this->params['fields'][$k]['unique']) && $this->params['fields'][$k]['unique']) {
                    $check = Db::get('SELECT `id` FROM `'.$this->params['table'].'` WHERE `'.$k.'`='.Db::quote($value).' AND `id`!='.intval($id));
                    if ($check) {
                        $unique_alert[] = $k;
                    }
                }
                $set[] = '`'.$k.'`='.Db::quote($value);
            }
        }

        if (sizeof($unique_alert))
            return $this->auto_edit($id, $unique_alert);

        if (!$id) {
            $sql = 'INSERT INTO `'.$this->params['table'].'` SET '.implode(', ', $set);
        } else {
            $sql = 'UPDATE `'.$this->params['table'].'` SET '.implode(', ', $set).' WHERE `id`='.$id;
        }
        Db::exec($sql);
        if (!$id) {
            $id = Db::lastId();
            Redirect::http(Conf::get('admin.url').'&status=saved&id='.$id);
        }
        Redirect::http(Conf::get('url').(!isset($_GET['status']) ? '&status=saved' : ''));
    }

    private function    auto_delete($id) {
        if (isset($_POST['delete'])) {
            Db::exec('DELETE FROM `'.$this->params['table'].'` WHERE `id`='.intval($id));
            Redirect::http(Conf::get('admin.url'));
        }

        $values = [];
        foreach ($this->params['fields'] as $k => $v) {
            $values[$k] = isset($v['default']) ? $v['default'] : '';
        }

        if ($id) {
            $data = Db::get('SELECT * FROM `'.$this->params['table'].'` WHERE `id`='.$id);
            if ($data) {
                foreach ($values as $k => $v)
                    $values[$k] = $data[$k];
            }
        }

        $types = [];
        foreach ($this->params['fields'] as $k => $v) {
            $types[$k] = $this->getType($k);
        }

        $titles = [];
        foreach ($this->params['fields'] as $k => $v) {
            $titles[$k] = isset($v['title']) ? $v['title'] : ucfirst($k);
        }

        $status = '';
        if (isset($_GET['status']))
            $status = $_GET['status'];

        return View::partial('admin/delete', [
            'id' => $id,
            'itemtitle' => $this->params['item'],
            'types' => $types,
            'values' => $values,
            'titles' => $titles,
            'status' => $status
        ]);
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