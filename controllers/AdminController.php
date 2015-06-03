<?php

class                   AdminController extends Controller {
    public              $methods = [];
	public 				$viewParams = [];
	
    public function     index($p) {
        Auth::mustBeLogged(true);
		
		$this->setupView();
        $this->addMethods();
        $this->handleRequests();
        $this->showAdmin();
    }

	private function 	setupView() {
		$this->viewParams = [
			'content' => '',
			'selectedItem' => 'dashboard'
		];
	}

    private function    addMethods() {
        // Users
        $this->methods['users'] = new AdminTable([
            'mode' => 'auto',
            'table' => 'User',
            'title' => 'Utilisateurs',
            'fields' => [
                'email' => [
                    'type' => 'email',
                    'unique' => true,
                    'title' => _t("Adresse e-mail")
                ],
                'password' => [
                    'type' => 'password',
                    'title' => _t("Mot de passe")
                ],
                'firstname' => [
                    'type' => 'text',
                    'title' => _t("Prénom")
                ],
                'lastname' => [
                    'type' => 'text',
                    'title' => _t("Nom")
                ],
                'genre' => [
                    'type' => 'select',
                    'values' => [
                        'M.',
                        'Mme.'
                    ],
                    'title' => _t("Civilité")
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
        
        // Test for local method
        $this->methods['localMethodTest'] = new AdminTable([
	        'mode' => 'standalone',
	        'method' => 'testMethod'
        ]);
        
        // Test for anonymous function
        $this->methods['localMethodTest'] = new AdminTable([
	        'mode' => 'standalone',
	        'handler' => function() {
		        if (isset($_POST['validate'])) {
			        // Do register process
		        }
		        return View::display('admin/anonymousFunctionTest.php');
	        }
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

    }
}

?>