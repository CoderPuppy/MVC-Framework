<?php
echo "<pre>";
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
echo "Finished creating dirs";
file_put_contents(DIR . "index.php", "");
echo "\nCreated index.php";
file_put_contents(DIR . "config.php", file_get_contents("http://drewyoung1.kodingen.com/getter.php?loc=" . urlencode("https://github.com/drewyoung1/MVC-Framework/raw/master/config.php")));
echo "\nCreated config.php";
file_put_contents(DIR . "app/controllers/appController.php", file_get_contents("http://drewyoung1.kodingen.com/getter.php?loc=" . urlencode("https://github.com/drewyoung1/MVC-Framework/raw/master/app/controllers/appController.php")));
echo "\nCreated app/controllers/appController.php";
file_put_contents(DIR . "lib/base.php", file_get_contents("http://drewyoung1.kodingen.com/getter.php?loc=" . urlencode("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/base.php")));
echo "\nCreated lib/base.php";
file_put_contents(DIR . "lib/object.php", file_get_contents("http://drewyoung1.kodingen.com/getter.php?loc=" . urlencode("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/object.php")));
echo "\nCreated lib/object.php";
file_put_contents(DIR . "lib/sammy.php", file_get_contents("http://drewyoung1.kodingen.com/getter.php?loc=" . urlencode("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/sammy.php")));
echo "\nCreated lib/sammy.php";
file_put_contents(DIR . "lib/views.php", file_get_contents("http://drewyoung1.kodingen.com/getter.php?loc=" . urlencode("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/views.php")));
echo "\nCreated lib/views.php";
file_put_contents(DIR . "lib/controllers.php", file_get_contents("http://drewyoung1.kodingen.com/getter.php?loc=" . urlencode("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/controllers.php")));
echo "\nCreated lib/controllers.php";
file_put_contents(DIR . "lib/model.php", file_get_contents("http://drewyoung1.kodingen.com/getter.php?loc=" . urlencode("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/model.php")));
echo "\nCreated lib/model.php";
file_put_contents(DIR . "lib/router.php", file_get_contents("http://drewyoung1.kodingen.com/getter.php?loc=" . urlencode("https://github.com/drewyoung1/MVC-Framework/raw/master/lib/router.php")));
echo "\nCreated lib/router.php";
$base_url = dirname(getenv("SCRIPT_NAME"));
$contents = <<<HT
RewriteEngine On
RewriteBase $base_url/

RewriteCond %{REQUEST_FILENAME} -d
RewriteCond %{REQUEST_FILENAME} =lib [OR]
RewriteCond %{REQUEST_FILENAME} =app [OR]
RewriteCond %{REQUEST_FILENAME} =tmp [OR]
RewriteCond %{REQUEST_FILENAME} =err
RewriteRule (.*) index.php/$1

RewriteCond %{REQUEST_FILENAME} -F
RewriteCond %{REQUEST_FILENAME} offline_setup\.php [OR]
RewriteCond %{REQUEST_FILENAME} online_setup\.php [OR]
RewriteCond %{REQUEST_FILENAME} \.htaccess [OR]
RewriteCond %{REQUEST_FILENAME} config\.php [OR]
RewriteCond %{REQUEST_FILENAME} base\.php [OR]
RewriteCond %{REQUEST_FILENAME} sammy\.php [OR]
RewriteCond %{REQUEST_FILENAME} views\.php [OR]
RewriteCond %{REQUEST_FILENAME} controllers\.php [OR]
RewriteCond %{REQUEST_FILENAME} model\.php [OR]
RewriteCond %{REQUEST_FILENAME} router\.php [OR]
RewriteCond %{REQUEST_FILENAME} object\.php [OR]
RewriteCond %{REQUEST_FILENAME} \.gitignore [OR]
RewriteCond %{REQUEST_FILENAME} README\.md
RewriteRule (.*) index.php/$1

RewriteCond %{REQUEST_URI} lib [OR]
RewriteCond %{REQUEST_URI} app [OR]
RewriteCond %{REQUEST_URI} tmp [OR]
RewriteCond %{REQUEST_URI} err
RewriteRule (.*) index.php/$1

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule (.*) index.php/$1
HT;
file_put_contents(DIR . ".htaccess", $contents);
echo "\nCreated .htaccess";
echo "</pre>";
?>