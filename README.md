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

You can specify parameters by setting a special tag between braces: `{paramName}`.
If you want the last param to be optional, add an interrogation mark at the end: `{optionalParam}?`.

If you want to match an infinite number of parameters behind your route, just 
add a star (`*`) at the end: `/api/v1/*`.

The name of the route object is useful to generate an url into the current language :

```php
<?php

$url = Argv::createUrl('login', [
    'mandatory-param' => 'aValue',
    'optional-param' => ''
]);

?>
```

Database
--------

The database connection is managed by PDO, and accessible via `Db` object. You
can write your own SQL queries, or use the MongoDB like syntax :

```php
<?php

// Get a single item
// SQL
$user = Db::get('SELECT id, email FROM User WHERE id=' . Db::quote($_GET['id']));
// MongoDB like
$user = Db::getInstance()->User->findOne(['id' => $_GET['id']], ['id', 'email']);
echo $user->id . ': ' . $user->email;


// Get a list of items
// SQL
$users = Db::query('SELECT id, email FROM User WHERE enabled = 1 AND (id IN (1, 2, 3) OR id = 42) ORDER BY id DESC, email ASC LIMIT 1, 10');
// MongoDB like
$users = Db::getInstance()->User->find([
    'enabled' => 1,
    'id' => [
        '$or' => [
            '$in' => [1, 2, 3],
            '$eq' => 42
        ]
    ]
], ['id', 'email'], ['id' => -1, 'email' => 1], '1, 10');
foreach ($users as $user)
    echo $user->id . ': ' . $user->email . '<br />';


// Update
// SQL
Db::exec('UPDATE User SET enabled = 0 WHERE id = 10');
// MongoDB like
Db::getInstance()->User->update(['id' => 10], ['enabled' => 0]);


// Delete
// SQL
Db::exec('DELETE FROM User WHERE id = 42');
// MongoDB like
Db::getInstance()->User->remove(['id' => 42]);


// Insert
// SQL
Db::exec('INSERT INTO User (email, enabled) VALUES ("test@test.com", 1)');
$id = Db::lastId();
// MongoDB like
$id = Db::getInstance()->User->insert([
    'email' => 'test@test.com',
    'enabled' => 1
]);


// Save : only for MongoDB Like : if id field is in data, updates, or insert
$id = Db::getInstance()->User->save([
    'email' => 'test@test.com',
    'enabled' => 1
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

Models
------
The models in **42php** aren't the MVC models, but more auto-managed objects. 
It represents a single item of the database.

```php
<?php

class                           User extends Model {
    public                      $email;
    public                      $password;
    public                      $firstname;
    public                      $lastname;
    public                      $genre;
    public                      $registered;
    public                      $admin = false;
    public                      $slug;
    public                      $lang;
    public                      $photo;
    
    /*
    ** This method is automatically called before saving the object
    */
    public function             beforeSave() {
        if ($this->slug == '') {
            $base = Text::slug($this->firstname).'-'.Text::slug($this->lastname);
            $suffix = '';
            $res = true;
            while ($res) {
                $res = Db::getInstance()->User->findOne([
                    'slug' => $base . $suffix
                ]);
                if ($res)
                    $suffix = $suffix == '' ? 1 : intval($suffix) + 1;
            }
            $this->slug = $base . $suffix;
        }
    }

    public function             setPassword($newPassword) {
        $this->password = Hash::blowfish($newPassword);
        return $this;
    }

    public function             testPassword($password) {
        return Hash::same($password, $this->password);
    }
    
    /*
    ** Override of the Model::toJson method, returning data to export to API
    */
    public function             toJson($forSession = false) {
        $d = parent::toJson();
        unset($d['password']);
        if (!$forSession)
            unset($d['admin']);
        return $d;
    }
}

// Usage
$user = new User(); // New user
$user = new User(42); // Load the user id n°42

$user->email = 'test@test.com';
$user->setPassword('MyNewPassword');

$user->save();
$newUser = $user->duplicate();

$newUser->admin = true;
$newUser->save();
echo 'The new user ID is ' . $newUser->id;

$user->delete();

// Find all users who are not admin, to change the passwords
$users = User::find([
    'admin' => false
]);
foreach ($users as $user) {
    $user->setPassword( Text::random(8) );
    $user->save();
}

// Find one user
$user = User::findOne([
    'lastname' => 'Doe'
]);
if (!$user)
    echo "No user";
else
    var_dump($user->toJson());

?>
```


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

`/config/global.json`
```json
{
    "pdo": {
        "dsn": "mysql:host=localhost;dbname=mydb"
    }
}
```

`test.php`
```php
<?php

// Get the pdo.dsn value. If this value isn't in the configuration data, Conf::get returns the second parameter as a default value
echo "The PDO DSN is : " . Conf::get('pdo.dsn', 'mysql:host=locahost;dbname=cms');
// Prints : The PDO DSN is : mysql:host=localhost;dbname=mydb

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


### Add a custom panel

You can also create a custom panel, handled by an anonymous function :

```php
<?php

$this->methods['dashboard'] = new AdminTable([
    'mode' => 'standalone',
    'title' => _t('Tableau de bord'),
    'icon' => 'fi-home',
    'handler' => function() {
        return View::partial('admin/dashboard');
    }
]);

?>
```


A/B testing
----------

**42php** comes with an integrated AB testing tool, with unlimited number of 
tested pages. All you have to do is to call `AB::render` or `AB::partial` the 
same way you call the `View` class.

You'll have all the stats in the admin panel.

```php
<?php

return AB::render(['dir/file1', 'dir/file2', 'dir/file3'], [
    // view parameters
]);

?>
```

In the views, to track clicks for conversion stats, you can use `AB::link` and 
`AB::ajax` methods to track it :

```php
<div class="row">
    <div class="small-12 column">
        <a href="<?= AB::link("/register", "mainRegisterButton") ?>">Register !</span>
        <!-- First parameter is the url, second is an optional string to identify different button, for example -->
        
        
        <form method="post" action="/anUrl" onsubmit="<?= AB::ajax("secondAction") ?>">
            <!-- The AB::click method has only the optional string -->
            <button>Do the action</button>
        </form>
    </div>
</div>
```


Gallery Lightbox
----------------
**42php** comes with a simple lightbox, with Facebook-like design. You just have to set a bunch of parameters on any tag : 

 * `data-viewer` *(mandatory)*: ID of the gallery. Doesn't match any DOM ID, it's only to set multiple images into the same viewer.
 * `data-viewer-src` *(optional)*: URL of the full image. It's useful when you want to open viewer from a link, or other non-img tag, or from a thumbnail.
 * `data-height` *(optional)*: Height of the full image. It's useful when you want to open viewer from a link, or other non-img tag, or from a thumbnail.
 * `data-width` *(optional)*: Width of the full image. It's useful when you want to open viewer from a link, or other non-img tag, or from a thumbnail.
 * `data-viewer-column` *(optional)*: Used to append a right column into the viewer. Useful if you want to put content like users, comments, etc ...
 
Viewer works too with any AJAX loaded content.

`test.html`
```html
<link rel="stylesheet" type="text/css" href="/lib/viewer/viewer.css" />
<script type="text/javascript" src="/lib/viewer/viewer.js"></script>

<!-- Single image -->
<img src="/images/test1.jpg" alt="" data-viewer="soloimage" />

<!-- Multiple images with thumbnails -->
<img src="/images/test2-tmb.jpg" alt="" data-viewer="gallery" data-viewer-src="/images/test2.jpg" />
<img src="/images/test3-tmb.jpg" alt="" data-viewer="gallery" data-viewer-src="/images/test3.jpg" />
<img src="/images/test4-tmb.jpg" alt="" data-viewer="gallery" data-viewer-src="/images/test4.jpg" />

<!-- Story with one image and user details -->
<img src="/images/test5.jpg" data-viewer="story" />
<div data-viewer-column="story">
    <h3>Guillaume Gagnaire</h3>
    <p>
        This picture has been taken in the south of France, in Bordeaux.
    </p>
</div>
```