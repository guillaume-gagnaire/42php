<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>42php - Installation</title>
        <link rel="stylesheet" href="/lib/foundation/css/foundation.css" />
        <script src="/lib/foundation/js/vendor/modernizr.js"></script>
    </head>
    <body>
        <nav class="top-bar" data-topbar role="navigation">
            <ul class="title-area">
                <li class="name">
                    <h1><a href="#">42php - Setup</a></h1>
                </li>
            </ul>
        </nav>
        <div class="row">
            <div class="small-12 column">
                <p style="margin-top: 40px">Fill the form below with your database credentials, and the admin credentials.</p>
                <form method="get" action="/scripts/createDB.php" target="result">
                    <fieldset>
                        <legend>Database</legend>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Host
                                    <input type="text" name="host" placeholder="" value="localhost" />
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>DB name
                                    <input type="text" name="dbname" placeholder="" />
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Username
                                    <input type="text" name="user" placeholder="" />
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Password
                                    <input type="password" name="pass" placeholder="" />
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Prefix
                                    <input type="text" name="prefix" placeholder="" />
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Admin account</legend>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>First name
                                    <input type="text" name="firstname" placeholder="" />
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Last name
                                    <input type="text" name="lastname" placeholder="" />
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Email
                                    <input type="text" name="adminmail" placeholder="" />
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-12 columns">
                                <label>Password
                                    <input type="password" name="adminpass" placeholder="" />
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    <div style="text-align: center">
                        <button>Install 42php</button>
                    </div>
                </form>
                <iframe name="result" style="height: 500px; border: none; width: 100%;"></iframe>
            </div>
        </div>

        <script src="/lib/foundation/js/vendor/jquery.js"></script>
        <script src="/lib/foundation/js/foundation.min.js"></script>
        <script type="text/javascript">
            $(document).foundation();
        </script>
    </body>
</html>