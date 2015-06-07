42php
=====

**42php** is a simple framework to create websites faster than ever. It comes 
with admin panel, easily upgradable, some UI libs like Slick for slideshows, 
Foundation 5 for templating, responsive, etc ...

Librairies and technologies
---------------------------

* PHP >= 5.4
* MySQL
* Foundation 5 (http://foundation.zurb.com/)
* jQuery (https://jquery.com/)
* AngularJS (https://angularjs.org/)
* Foundation Icons (http://zurb.com/playground/foundation-icon-fonts-3)
* Pickadate (http://amsul.ca/pickadate.js/)
* Slick (http://kenwheeler.github.io/slick/)

Routing
-------
To create a route in **42php**, you just have to append an object to 
`/config/routes.json`

```json
"login": {
    "controller": "AuthController@login",
    "routes": {
        "fr": "/connexion/{mandatory-param}/{optional-param}?",
        "en": "/login/{mandatory-param}/{optional-param}?"
    }
}
```

The `controller` variable indicate which controller and method call: `ControllerName@MethodName`.

There is a route for each language implemented on the website. If you don't set
the route for a specific language, this page will never be reachable in this
language.

You can specify parameters by setting a special tag between braces: `{paramName}`. If you want the last param to be optional, add an interrogation mark at the end: `{optionalParam}?`.

If you want to match an infinite number of parameters behind your route, just add a star (`*`) at the end: `/api/v1/*`.

The name of the route object is useful to generate an url into the current language :

```php
<?php

$url = Argv::createUrl('login', [
    'mandatory-param' => 'aValue',
    'optional-param' => ''
]);

?>
```

Controllers
-----------

To create a controller, just create a class into the `/controllers` directory, 
which extends `Controller` class.

```php
<?php

class                       TestController extends Controller {
    public function         foo($p) {
        return 'My bar is ' . $p->status;
    }
}

echo Controller::run('TestController@foo', [
    'status' => 'opened'
]);
// Prints: My bar is opened

?>
```

Your methods can accept a single parameter, which is an object of all the 
parameters found in the route, or manually passed to `Controller::run`.

Views
-----

Views are PHP files which are simply executed in a buffer context. The params who
are given to the view are accessible directly by a variable, which has the same 
name.

Views are located in the `/views` directory. You can store views in subdirectories.

`testView.php`
```php
<?php

echo View::render('subdir/index', [
    'myVar' => [1, 2, 3, 4, 5]
]);

?>
```

`/views/subdir/index.php`
```php
<div class="row">
    <div class="small-12 column">
        List of the content of myVar : 
        <ul>
            <?php foreach ($myVar as $nb) { ?>
                <li><?= $nb ?></li>
            <?php } ?>
        </ul>
    </div>
</div>
```

The `View::render` method will auto-generate a full HTML5 template, and will put
your view between `<body>` and `</body>` tags.

If you just want to render your view, use `View::partial` with same parameters.

Internationalization
--------------------

The languages implemented into your website are configurable into the 
`/config/global.json` file.

The translation files are in `/i18n/lang.json`.

To get a translation, you can use two methods : `i18n::get()` or `_t()`, with
same parameters :

`/views/test.php`
```php
<h1><?= _t("Hello %s, how are you ?", [
    "Guillaume"
]) ?></h1>
```

The translation string is used with `vsprintf`, then you can use all the `*printf`
syntax.

When a translation string isn't referenced into the language file, the system will
automatically add it in the current translation file. Then, you'll just have to
translate it.

Configuration
-------------

The global configuration of your website is in the `/config/global.json` file.

For security reasons, the DB configuration is in the `/config/db.php` file.

You can, at the runtime, access or modify the configuration with a set of methods :

```php
<?php

// Get the pdo.dsn value. If this value isn't in the configuration data, Conf::get returns the second parameter as a default value
echo "The PDO DSN is : " . Conf::get('pdo.dsn', 'mysql:host=locahost;dbname=cms');

// Set a value
Conf::set('foo.bar', []);

// Append a value to a configuration array
Conf::append('foo.bar2', 'value1');
Conf::append('foo.bar2', ['name' => 'value2']);

// returns: ['value1', ['name' => 'value2']]
Conf::get('foo.bar2');

// Remove foo.bar and foo.bar2
Conf::remove('foo');

?>
```

Session
-------
**42php** didn't use the PHP session system. In replacement, it stores the 
session data into MySQL, and can share the session between the website and a 
mobile application, for example.

The `Session` object have the same methods than the `Conf` object, and add the
`Session::save` method to save the session in the database, and the 
`Session::destroy` method to destroy the session.


Administration panel
--------------------
The admin panel for **42php** is, by default, accessible at the `/admin` path. 
You can eventually change it in the routes file: `/config/routes.json`

It comes with default panels, for managing users, blog posts and static pages.

To add a panel, just add entries into the `AdminController@addMethods` method.

### Add a automated panel

**42php** comes with an automated admin panel, you just have to tell him the 
table name, the fields and their types, and everything is auto-managed.

```php
<?php

// Here the gestion for users. We define the unique name as "users", for the table "User".
$this->methods['users'] = new AdminTable([
    'mode' => 'auto',
    'hidden' => false, // Set true if you want to not add this panel to the navigation
    'table' => 'User', // Table name
    'title' => _t('Utilisateurs'), // Title of the panel (used in the panel, and in the navigation)
    'item' => _t('un utilisateur'), // Title of a single item
    'icon' => 'fi-torsos-all', // Icon of the nav, directly added in <i> class
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
    'header' => 'photo|email|admin' // the fields for the columns, into the list view
]);

?>
```

Each field has a similary structure :
* **type**: the type of the field. You can pass a single string, or, if you need to put a parameter, you can pass an array: `['fieldType', 'param']`
* **title**: the title of the field.
* **default**: default value for this field.
* **unique**: a boolean. If this field must be unique, **42php** will automatically check if the user set value exists in the table.
* **ifEmpty**: if the user set value is an empty string, the value will be set by the returned value of this anonymous function.


#### Field types
There is a list of all types actually supported by **42php** :

Type | Description | Parameters
--- | --- | ---
text | A text input. | -
html | A HTML source. Directly implements a WYSIWYG editor. | -
int | An integer. | -
float | A float variable. | -
price | A price. Automatically formats it for display, and replaces `,` to `.` for when saving. | -
password | Set a new password, with blowfish hash (7 rounds) if the password is not empty and equals to the confirm password value. | -
image | An image upload field. If you don't select any picture, the field isn't changed. | If you want to resize it, put as parameter : "width x height", without spaces. Ex: "640x480"
file | A file upload field. Sames rules than `image` type. | -
email | An email input. Validates email. If the email isn't valid, it doesn't change. | -
date | A date input. Directly implements date picker. | -
datetime | A datetime input. Directly implements date and time picker. | -
bool | A checkbox, sets `1` or `0` in database. | -
select | A select input. | The parameter is an array with values. If non-associative, the option value field is filled with the same string as the displayed value.
hidden | A hidden input, used with `default` field to fill it. | -