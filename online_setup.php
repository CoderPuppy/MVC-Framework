<?php
define("DIR", dirname(realpath(__FILE__)) . "\\");

if(!file_exists(DIR . "lib/")) {
	mkdir(DIR . "lib");
}
if(!file_exists(DIR . "app/")) mkdir(DIR . "app");
if(!file_exists(DIR . "tmp/")) mkdir(DIR . "tmp");
if(!file_exists(DIR . "app/controllers/"))
	mkdir(DIR . "app/controllers");
if(!file_exists(DIR . "app/view/"))
	mkdir(DIR . "app/view");
if(!file_exists(DIR . "app/models/"))
	mkdir(DIR . "app/models");
echo "finished creating dirs ";
file_put_contents(DIR . "index.php", "");
file_put_contents(DIR . "config.php", file_get_contents("https://github.com/drewyoung1/MVC-Framework/raw/master/config.php"));
file_put_contents(DIR . "app/controllers/appController.php", file_get_contents("https://github.com/drewyoung1/MVC-Framework/raw/master/app/controllers/appController.php"));
file_put_contents(DIR . "lib/base.php", file_get_contents("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/base.php"));
file_put_contents(DIR . "lib/object.php", file_get_contents("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/object.php"));
file_put_contents(DIR . "lib/sammy.php", file_get_contents("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/sammy.php"));
file_put_contents(DIR . "lib/views.php", file_get_contents("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/views.php"));
file_put_contents(DIR . "lib/controllers.php", file_get_contents("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/controllers.php"));
file_put_contents(DIR . "lib/model.php", file_get_contents("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/model.php"));
file_put_contents(DIR . "lib/router.php", file_get_contents("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/router.php"));
$base_url = dirname(getenv("SCRIPT_NAME"));
$contents = <<<HT
RewriteEngine On
RewriteBase $base_url

RewriteCond %{REQUEST_FILENAME} -d
RewriteRule (.*) index.php/$1

RewriteCond %{REQUEST_FILENAME} -F
RewriteRule (.*) index.php/$1

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule (.*) index.php/$1
HT;
file_put_contents(DIR . ".htaccess", $contents);
?>