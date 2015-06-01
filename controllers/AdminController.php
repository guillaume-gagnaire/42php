<?php

class                   AdminController extends Controller {
    public              $methods = [];

    public function     index($p) {
        Auth::mustBeLogged(true);

        $this->addMethods();
        $this->handleRequests();
        $this->showAdmin();
    }

    private function    addMethods() {
        // Users
        $this->methods['Users'] = new AdminTable([
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
    }

    private function    handleRequests() {

    }

    private function    showAdmin() {

    }
}

?>