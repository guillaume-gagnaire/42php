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
            'module' => 'dashboard',
	        'handler' => function() {
		        return View::partial('admin/dashboard');
	        }
        ]);
        
        
        // Users
        $this->methods['users'] = new AdminTable([
            'mode' => 'auto',
            'table' => 'User',
            'module' => 'users',
            'title' => _t('Utilisateurs'),
            'item' => _t('un utilisateur'),
            'icon' => 'fi-torsos-all',
            'fields' => [
                'genre' => [
                    'type' => 'select',
                    'values' => [
                        'M.',
                        'Mme.'
                    ],
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
	                'type' => 'image',
	                'title' => 'Photo de profil'
                ],
                'registered' => [
                    'type' => 'datetime',
                    'default' => 'now',
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
                    'title' => _t("Référence")
                ],
                'lang' => [
                    'type' => 'select',
                    'values' => i18n::$__acceptedLanguages,
                    'title' => _t("Langue")
                ]
            ]
        ]);
    }

    private function    handleRequests() {
		$toLoad = 'dashboard';
		if (isset($_GET['module']))
			$toLoad = $_GET['module'];

		if (!isset($this->methods[$toLoad])) {
			$this->viewParams['content'] = View::partial('404');
		} else {
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