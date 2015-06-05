<?php

class                   AdminController extends Controller {
    public              $methods = [];
	public 				$viewParams = [];
	
    public function     index($p) {
        Auth::mustBeLogged(true);
		
        $this->addMethods();
		$this->setupView();
        $this->handleRequests();
        return $this->showAdmin();
    }

	private function 	setupView() {
		$this->viewParams = [
			'content' => '',
			'selectedItem' => 'dashboard',
			'nav' => ''
		];
        Conf::set('page.js', []);
        Conf::set('page.css', []);
        Conf::append('page.js', '/lib/admin/menu.js');
        Conf::append('page.css', 'https://fonts.googleapis.com/css?family=Roboto:400,300');
        Conf::append('page.css', '/lib/foundation-icons/foundation-icons.css');
        Conf::append('page.css', '/lib/admin/style.css');
	}
	
	private function 	getNav() {
		$str = '';
		foreach ($this->methods as $k => $v) {
			$str .= '<li'.($this->viewParams['selectedItem'] == $k ? ' class="active"' : '').'>
				<a href="'.Argv::createUrl('admin').'?module='.$k.'">'.(isset($v->params['icon']) ? '<i class="'.$v->params['icon'].'"></i>' : '').(isset($v->params['title']) ? $v->params['title'] : ucfirst($k)).'</a>
			</li>';
		}
		return $str;
	}

    private function    addMethods() {
        // Test for anonymous function
        $this->methods['dashboard'] = new AdminTable([
	        'mode' => 'standalone',
            'title' => _t('Tableau de bord'),
            'icon' => 'fi-home',
	        'handler' => function() {
		        return View::partial('admin/dashboard');
	        }
        ]);
        
        
        // Users
        $this->methods['users'] = new AdminTable([
            'mode' => 'auto',
            'table' => 'User',
            'title' => _t('Utilisateurs'),
            'item' => _t('un utilisateur'),
            'icon' => 'fi-torsos-all',
            'fields' => [
                'genre' => [
                    'type' => ['select', [
                        'M.',
                        'Mme.'
                    ]],
                    'title' => _t("Civilité")
                ],
                'firstname' => [
                    'type' => 'text',
                    'title' => _t("Prénom")
                ],
                'lastname' => [
                    'type' => 'text',
                    'title' => _t("Nom")
                ],
                'email' => [
                    'type' => 'email',
                    'unique' => true,
                    'title' => _t("Adresse e-mail")
                ],
                'password' => [
                    'type' => 'password',
                    'title' => _t("Mot de passe")
                ],
                'photo' => [
	                'type' => ['image', '256x256'],
	                'title' => 'Photo de profil'
                ],
                'registered' => [
                    'type' => 'datetime',
                    'default' => date('Y-m-d H:i:s'),
                    'title' => _t("Date d'inscription")
                ],
                'admin' => [
                    'type' => 'bool',
                    'default' => false,
                    'title' => _t("Administrateur")
                ],
                'slug' => [
                    'type' => 'text',
                    'unique' => true,
                    'title' => _t("Référence"),
                    'ifEmpty' => function() {
                        $base = Text::slug($_POST['firstname']).'-'.Text::slug($_POST['lastname']);
                        $suffix = '';
                        $res = true;
                        while ($res) {
                            $res = Db::getInstance()->User->findOne([
                                'slug' => $base . $suffix
                            ]);
                            if ($res)
                                $suffix = $suffix == '' ? 1 : intval($suffix) + 1;
                        }
                        return $base . $suffix;
                    }
                ],
                'lang' => [
                    'type' => ['select', i18n::$__acceptedLanguages],
                    'title' => _t("Langue")
                ]
            ],
            'header' => 'photo|email|registered|admin',
            'restrict' => []
        ]);
    }

    private function    handleRequests() {
		$toLoad = 'dashboard';
		if (isset($_GET['module']))
			$toLoad = $_GET['module'];

		if (!isset($this->methods[$toLoad])) {
			$this->viewParams['content'] = View::partial('404');
		} else {
            Conf::set('admin.module', $toLoad);
            Conf::set('admin.moduleTitle', $this->methods[$toLoad]->params['title']);
            Conf::set('admin.url', Argv::createUrl('admin').'?module='.$toLoad);
			$this->viewParams['content'] = $this->methods[$toLoad]->render();
			$this->viewParams['selectedItem'] = $toLoad;
		}
    }

    private function    showAdmin() {
	    $this->viewParams['nav'] = $this->getNav();
		return View::render('admin/index', $this->viewParams);
    }
}

?>