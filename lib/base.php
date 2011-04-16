<?php
define('BASE_PATH', dirname(realpath(__FILE__)) . '\\..\\');
define('APP_PATH', BASE_PATH . 'app\\');
define('LIB_PATH', BASE_PATH . 'lib\\');
define('TMP_PATH', BASE_PATH . 'tmp\\');

// function returner returns it's single argument
function returner($data) {
	return $data;	
}

include_once "/config.php";
include_once "object.php";
include_once "router.php";
include_once "model.php";
include_once "views.php";
include_once "controllers.php";
?>