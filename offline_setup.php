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
$contents = <<<'CONFIG'
<?php

	$GLOBALS['env'] = 'dev';

	define('CONTROLLER_ROOT',APP_PATH . "controllers\\");
  define('VIEW_ROOT',APP_PATH . "views\\");
  define('MODEL_ROOT',APP_PATH . "models\\");

?>
CONFIG;
file_put_contents(DIR . "config.php", $contents);
echo "\nCreated config.php";
$contents = <<<'ACONTROLLER'
<?php
	class AppController extends Controller{
		
	}
?>
ACONTROLLER;
file_put_contents(DIR . "app/controllers/appController.php", $contents);
echo "\nCreated app/controllers/appController.php";
$contents = <<<'BASE'
<?php
define('BASE_PATH', dirname(realpath(DIR)) . PATH_SEPREATOR . '..' . PATH_SEPERATOR);
define('APP_PATH', BASE_PATH . 'app' . PATH_SEPERATOR);
define('LIB_PATH', BASE_PATH . 'lib' . PATH_SEPERATOR);
define('TMP_PATH', BASE_PATH . 'tmp' . PATH_SEPERATOR);

// function returner returns it's single argument
function returner($data) {
	return $data;	
}

include_once PATH_SEPREATOR . "config.php";
include_once "object.php";
include_once "router.php";
include_once "model.php";
include_once "views.php";
include_once "controllers.php";
?>
BASE;
file_put_contents(DIR . "lib/base.php", $contents);
echo "\nCreated lib/base.php";
$contents = <<<'OBJ'
<?php
class Object {
	public static $__user_vars = array();
  
  public function __set($name, $value) {
    self::$__user_vars[$name] = $value;
  }
  
  public function __get($name) {
    return (isset(self::$__user_vars[$name])) ? self::$__user_vars[$name] : null;
  }
  
  public function get_user_vars($class) {
    $vars = get_object_vars($class);
    
    self::$__user_vars = array_merge($vars, self::$__user_vars);
  }
}
?>
OBJ;
file_put_contents(DIR . "lib/object.php", $contents);
echo "\nCreated lib/object.php";
$contents = <<<'SAMMY'
<?php
/**
* Sammy - A bare-bones PHP version of the Ruby Sinatra framework.
*
* @version    1.0
* @author    Dan Horrigan
* @license    MIT License
* @copyright  2010 Dan Horrigan
*/

function sammyGet($route, $callback) {
  Sammy::process($route, $callback, 'GET');
}

function sammyPost($route, $callback) {
  Sammy::process($route, $callback, 'POST');
}

function sammyPut($route, $callback) {
  Sammy::process($route, $callback, 'PUT');
}

function sammyDelete($route, $callback) {
  Sammy::process($route, $callback, 'DELETE');
}

function sammyAjax($route, $callback) {
  Sammy::process($route, $callback, 'XMLHttpRequest');
}

class Sammy {
  
  public static $route_found = false;
  
  public $uri = '';
  
  public $segments = '';
  
  public $method = '';
  
  public $format = '';
  
  public static function instance() {
    static $instance = null;
    
    if( $instance === null ) {
      $instance = new Sammy;
    }
    
    return $instance;
  }
  
  public static function run() {
    if( !static::$route_found ) {
      echo 'Route not defined!';
    }
    
    ob_end_flush();
  }
  
  public static function process($route, $callback, $type) {
    $sammy = static::instance();
    
    // Check for ajax
    if( $type == 'XMLHttpRequest' )
      $sammy->method = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : 'GET';
    
    if( static::$route_found || (!preg_match('@^'.$route.'(?:\.(\w+))?$@uD', $sammy->uri, $matches) || $sammy->method != $type) ) {
      return false;
    }
    //echo "execing: \$route: $route";
    
    // Get the extension
    $extension = $matches[count($matches)-1];
    $extension_test = substr($sammy->uri, -(strlen($extension)+1), (strlen($extension)+1));
    
    if( $extension_test == '.' . $extension )
      $sammy->format = $extension;
    else
    $sammy->format = "html";
    
    if($sammy->format == "htm")
    	$sammy->format = "html";
    
    static::$route_found = true;
    echo $callback($sammy);
  }
  
  public function __construct() {
    ob_start();
    $this->uri = $this->get_uri();
    $this->segments = explode('/', trim($this->uri, '/'));
    $this->method = $this->get_method();
  }
  
  public function segment($num) {
    $num--;
    
    // Remove the extension
    $this->segments[$num] = isset($this->segments[$num]) ? rtrim($this->segments[$num], '.' . $this->format) : null;
    
    return isset($this->segments[$num]) ? $this->segments[$num] : null;
  }
  
  protected function get_method() {
    return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
  }
  
  protected function get_uri($prefix_slash = true) {
  	if( isset($_SERVER['PATH_INFO']) ) {
  		$uri = $_SERVER['PATH_INFO'];
  	}elseif( isset($_SERVER['REQUEST_URI']) ) {
  		$uri = $_SERVER['REQUEST_URI'];
  		
  		if( strpos($uri, $_SERVER['SCRIPT_NAME']) === 0 ) {
  			$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
  		}elseif( strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0 ) {
  			$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
  		}
  		
  		// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
  		// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
  		if( strncmp($uri, '?/', 2) === 0 ) {
  			$uri = substr($uri, 2);
  		}
  		
  		$parts = preg_split('#\?#i', $uri, 2);
  		$uri = $parts[0];
  		
  		if( isset($parts[1]) ) {
  			$_SERVER['QUERY_STRING'] = $parts[1];
  			parse_str($_SERVER['QUERY_STRING'], $_GET);
  		}else {
  			$_SERVER['QUERY_STRING'] = '';
  			$_GET = array();
  		}
  		$uri = parse_url($uri, PHP_URL_PATH);
  	}else {
  		// Couldn't determine the URI, so just return false
  		return false;
  	}
  	
  	// Do some final cleaning of the URI and return it
  	return ($prefix_slash ? '/' : '').str_replace(array('//', '../'), '/', trim($uri, '/'));
  }
  
  public function format($name, $callback) {
    $sammy = static::instance();
    if( !empty($sammy->format) && strtolower($name) == strtolower($sammy->format) )
      echo $callback($sammy);
    else
    return false;
  }
}

$sammy = Sammy::instance();

?>
SAMMY;
file_put_contents(DIR . "lib/sammy.php", $contents);
echo "\nCreated lib/sammy.php";
$contents = <<<'VIEWS'
<?php

global $view;
$view = new stdClass();

class View extends Object{
	
	public function render() {
		if($GLOBALS['env'] == "dev") {
			echo "<pre>";
			echo "\$_SERVER:\n";
			print_r($_SERVER);
			echo "</pre>";
			echo "<pre>";
			echo "\$_REQUEST:\n";
			print_r($_REQUEST);
			echo "</pre>";
			echo "<pre>";
			echo "\$sammy:\n";
			print_r($GLOBALS['sammy']);
			echo "</pre>";
		}
	}
	
}

function view($name) {
	global $view;
	
  //find view path
  $first_view_path = VIEW_ROOT . $name . ".tmpl";
  
  $second_view_path = VIEW_ROOT . $name . ".php";
  
  $second_class = ucfirst(strtolower($name))."View";
  
  $third_view_path = VIEW_ROOT . $name;
  
  $path_to_use = null;
  
  $class_to_use = null;
  
  // find the path to use
  if( file_exists($first_view_path) ) {
  	
  	$dounlink = true;
  	
  	$filename = "View".(intval(time())*rand(1,10));
  	
    $path_to_use = TMP_PATH . $filename . ".php";
    
    $class_to_use = $filename;
    
    file_put_contents($path_to_use, "<?php\nclass $filename extends View {\n\tpublic function render() {\n\t\t\?>".file_get_contents($first_view_path)."<?php\n\t}\n}\n?>");
    
  } elseif( file_exists($second_view_path) ) {
  	
  	$path_to_use = $second_view_path;
  	
  	$class_to_use = $second_class;
  	
  	$dounlink = false;
  	
  } elseif( file_exists($third_view_path) ) {
  	
  	$dounlink = true;
  	
  	$filename = "View".(intval(time())*rand(1,10));
  	
  	$class_to_use = $filename;
  	
    $path_to_use = TMP_PATH . $filename . ".php";
    
    file_put_contents($path_to_use, "<?php\nclass $filename extends View {\n\tpublic function render() {\n\t\t?>".file_get_contents($third_view_path)."<?php\n\t}\n}\n?>");
    
  } else
  die("No such view: <strong>$name</strong> at: <strong>$first_view_path</strong> or: <strong>$second_view_path</strong> or: <strong>$third_view_path</strong>");
  
  include $path_to_use;
  
  if(class_exists($class_to_use)) {
  	
  	$viewo = new $class_to_use();
  	
  	$viewo->get_user_vars($view);
  	
    if(is_callable(array($viewo,"render")))
    	$viewo->render();
  } else
  die("No such class <strong>$class_to_use</strong>");
  
  if($dounlink)
  	unlink($path_to_use);
}

?>
VIEWS;
file_put_contents(DIR . "lib/views.php", $contents);
echo "\nCreated lib/views.php";
$contents = <<<'CONTROLLERS'
<?php
function controller($name) {
	return action($name,"index");
}

function action($name,$action) {
	include_once CONTROLLER_ROOT . "appController.php";
	global $cname, $caction;
	$cname = $name;
	$caction = $action;
	return returner(function($r) {
			global $cname, $caction;
			// find the controller file
			$controller_path = CONTROLLER_ROOT . $cname . "Controller.php";
			
			// load the controller if it exists
			if(file_exists($controller_path))
				include_once $controller_path;
			else
			die("No such controller: ".$name);
			
			$class_name = ucfirst($cname) . "Controller";
			
			if(class_exists($class_name))
				$controller = new $class_name();
			else
			die("No such controller: ".$cname);
			
			// run the action
			if(is_callable(array($controller, $caction)))
				$controller->$caction($r);
	});
}
class Controller extends Object{
	public function renderView($name) {		
		//find view path
		$first_view_path = VIEW_ROOT . $name . ".tmpl";
		
		$second_view_path = VIEW_ROOT . $name . ".php";
		
		$second_class = ucfirst(strtolower($name))."View";
		
		$third_view_path = VIEW_ROOT . $name;
		
		$path_to_use = null;
		
		$class_to_use = null;
		
		// find the path to use
		if( file_exists($first_view_path) ) {
			
			$dounlink = true;
			
			$filename = "View".(intval(time())*rand(1,10));
			
			$path_to_use = TMP_PATH . $filename . ".php";
			
			$class_to_use = $filename;
			
			file_put_contents($path_to_use, "<?php\nclass $filename extends View {\n\tpublic function render() {\n\t\t\?>".file_get_contents($first_view_path)."<?php\n\t}\n}\n?>");
			
		} elseif( file_exists($second_view_path) ) {
			
			$path_to_use = $second_view_path;
			
			$class_to_use = $second_class;
			
			$dounlink = false;
			
		} elseif( file_exists($third_view_path) ) {
			
			$dounlink = true;
			
			$filename = "View".(intval(time())*rand(1,10));
			
			$class_to_use = $filename;
			
			$path_to_use = TMP_PATH . $filename . ".php";
			
			file_put_contents($path_to_use, "<?php\nclass $filename extends View {\n\tpublic function render() {\n\t\t?>".file_get_contents($third_view_path)."<?php\n\t}\n}\n?>");
			
		} else
		die("No such view: <strong>$name</strong> at: <strong>$first_view_path</strong> or: <strong>$second_view_path</strong> or: <strong>$third_view_path</strong>");
		
		include $path_to_use;
		
		if(class_exists($class_to_use)) {
			
			$viewo = new $class_to_use();
			
			$viewo->get_user_vars($this);
			
			if(is_callable(array($viewo,"render")))
				$viewo->render();
		} else
		die("No such class <strong>$class_to_use</strong>");
		
		if($dounlink)
			unlink($path_to_use);
	}
	
	public function redirectTo($url) {
		header('Location: ' . $url);	
	}
}
?>
CONTROLLERS;
file_put_contents(DIR . "lib/controllers.php", $contents);
echo "\nCreated lib/controllers.php";
$contents = <<<'MODEL'
<?php
class Model extends Object{
	static $table_name;
	static $MVars = array();
	static $PKey;
	static $dbinfo;
	
	public static function create($dbinfo) {
		static::$dbinfo = $dbinfo;
		
		// get the class of the model
		$class_name = get_called_class();
		
		// sets the name of the table to be created to the class name lowercase
		static::$table_name = strtolower($class_name);
		
		// gets the host
		$host = isset($dbinfo['server'])?$dbinfo['server']:"localhost";
		
		// gets the user
		$user = isset($dbinfo['user'])?$dbinfo['user']:"root";
		
		// gets the passwd
		$pass = isset($dbinfo['pass'])?$dbinfo['pass']:"";
		
		// gets the db
		$db = isset($dbinfo['db'])?$dbinfo['db']:"mvc";
		
		// connects to the mysql server
		$con = new mysqli($host, $user, $pass, $db);
		
		$con->query("DROP TABLE IF EXISTS ".static::$table_name."");
		
		// sets up the sql
		$sql = "CREATE TABLE ".$dbinfo['db'].".".static::$table_name." (\n";
		
		// creates var cols
		$cols = array();
		
		// loops through vars to save & generate sql to add fields
		foreach(static::$MVars as $desc) {
			
			$cols[count($cols)] = (isset($desc->data['name'])?$desc->data['name']:$desc->name) . " " .(isset($desc->data['type'])?$desc->data['type']:"VARCHAR(20)");
			
		}
		
		if(isset(static::$PKey)) {
			$pkey = null;
			if(is_array(static::$PKey)) {
				$keys = array();
				foreach(static::$PKey as $key) {
					$keys[count($keys)] = $key->data['name'];
				}
				$pkey = join(", ",$keys);
			} elseif(is_object(static::$PKey))
			$pkey = static::$PKey->data['name'];
			$cols[count($cols)] = "PRIMARY KEY ($pkey)";
		}
		
		// add field generation code to sql
		$sql = $sql . implode(",\n",$cols) . "\n);";
		
		// execute query
		$res = mysqli_query($con, $sql) or die(mysqli_error($con));
		
	}
	
	public static function open($dbinfo) {
		static::$dbinfo = $dbinfo;
		
		// get the class of the model
		$class_name = get_called_class();
		
		// sets the name of the table to be created to the class name lowercase
		static::$table_name = strtolower($class_name);
		
		// gets the host
		$host = isset($dbinfo['host'])?$dbinfo['host']:"localhost";
		
		// gets the user
		$user = isset($dbinfo['user'])?$dbinfo['user']:"root";
		
		// gets the passwd
		$pass = isset($dbinfo['pass'])?$dbinfo['pass']:"";
		
		// gets the db
		$db = isset($dbinfo['db'])?$dbinfo['db']:"mvc";
		
		// connects to the mysql server
		$con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_error($con));
		
		// sets up the sql
		$sql = "CREATE TABLE IF NOT EXISTS ".$dbinfo['db'].".".static::$table_name." (\n";
		
		// creates var cols
		$cols = array();
		
		// loops through vars to save & generate sql to add fields
		foreach(static::$MVars as $desc) {
			
			$cols[count($cols)] = (isset($desc->data['name'])?$desc->data['name']:$desc->name) . " " .(isset($desc->data['type'])?$desc->data['type']:"VARCHAR(20)");
			
		}
		
		if(isset(static::$PKey)) {
			$pkey = null;
			if(is_array(static::$PKey)) {
				$keys = array();
				foreach(static::$PKey as $key) {
					$keys[count($keys)] = $key->data['name'];
				}
				$pkey = join(", ",$keys);
			} elseif(is_object(static::$PKey))
			$pkey = static::$PKey->data['name'];
			$cols[count($cols)] = "PRIMARY KEY ($pkey)";
		}
		
		// add field generation code to sql
		$sql = $sql . implode(",\n",$cols) . "\n);";
		
		// execute query
		$res = mysqli_query($con, $sql) or die(mysqli_error($con));
		
	}
	
	public function add() {
		
		$dbinfo = static::$dbinfo;
		
		// gets the host
		$host = isset($dbinfo['host'])?$dbinfo['host']:"localhost";
		
		// gets the user
		$user = isset($dbinfo['user'])?$dbinfo['user']:"root";
		
		// gets the passwd
		$pass = isset($dbinfo['pass'])?$dbinfo['pass']:"";
		
		// gets the db
		$db = isset($dbinfo['db'])?$dbinfo['db']:"mvc";
		
		$sql = "INSERT INTO ".$db.".".static::$table_name." VALUES(";
		
		$cols = array();
		
		foreach(static::$MVars as $var) {
			$name = $var->name;
			$cols[count($cols)] = "'".json_encode($this->$name)."'";
			
		}
		
		$sql = $sql . join(', ', $cols) . ");";
		
		// debug $sql
		echo "<pre>";
		echo "\$sql:\n";
		print_r($sql);
		echo "</pre>";
		
		// connects to the mysql server
		$con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_error($con));
		
		// execute insert
		$res = mysqli_query($con, $sql);
		
		// debug $res
		echo "<pre>";
		echo "\$res:\n";
		print_r($res);
		echo "</pre>";
		
	}
	
	public function isSaved() {
		
		$dbinfo = static::$dbinfo;
		
		// gets the host
		$host = isset($dbinfo['host'])?$dbinfo['host']:"localhost";
		
		// gets the user
		$user = isset($dbinfo['user'])?$dbinfo['user']:"root";
		
		// gets the passwd
		$pass = isset($dbinfo['pass'])?$dbinfo['pass']:"";
		
		// gets the db
		$db = isset($dbinfo['db'])?$dbinfo['db']:"mvc";
		
		// start sql
		$sql = "SELECT * FROM $db.".static::$table_name." WHERE ";
		
		// create cols
		$cols = array();
		
		foreach(static::$MVars as $var) {
			$vname = $var->name;
			$cols[count($cols)] = $var->data['name'] . " = '" . json_encode($this->$vname) . "'";
		}
		
		$sql = $sql . join(" AND ", $cols);
		
		// debug $sql
		echo "<pre>";
		echo "\$sql:\n";
		print_r($sql);
		echo "</pre>";
				
		// connects to the mysql server
		$con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_error($con));
		
		// execute insert
		$res = mysqli_query($con, $sql);
		
		// debug $res
		echo "<pre>";
		echo "\$res:\n";
		print_r($res);
		echo "</pre>";
		
		return mysqli_num_rows($res) == 1;
		
	}
	
	public function update($var_to_update, $new_val) {
		
		
		$dbinfo = static::$dbinfo;
		
		// gets the host
		$host = isset($dbinfo['server'])?$dbinfo['server']:"localhost";
		
		// gets the user
		$user = isset($dbinfo['user'])?$dbinfo['user']:"root";
		
		// gets the passwd
		$pass = isset($dbinfo['pass'])?$dbinfo['pass']:"";
		
		// gets the db
		$db = isset($dbinfo['db'])?$dbinfo['db']:"mvc";
		
		// start sql
		$sql = "UPDATE $db." . static::$table_name . " SET " . $var_to_update->data['name'] . "='" . json_encode($new_val) . "'";
		
		// create cols
		$where_cols = array();
		
		foreach(static::$MVars as $var) {
			$vname = $var->name;
			$where_cols[count($where_cols)] = $var->data['name'] . " = '" . json_encode($this->$vname) . "'";
		}
		
		$sql = $sql . " WHERE " . join(" AND ", $where_cols);
				
		// connects to the mysql server
		$con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_error($con));
		
		// execute insert
		$res = mysqli_query($con, $sql);
		
		$vname = $var_to_update->name;
		$this->$vname = $new_val;
	}
	
	public function delete() {
		
		$dbinfo = static::$dbinfo;
		
		// gets the host
		$host = isset($dbinfo['host'])?$dbinfo['host']:"localhost";
		
		// gets the user
		$user = isset($dbinfo['user'])?$dbinfo['user']:"root";
		
		// gets the passwd
		$pass = isset($dbinfo['pass'])?$dbinfo['pass']:"";
		
		// gets the db
		$db = isset($dbinfo['db'])?$dbinfo['db']:"mvc";
		
		// start sql
		$sql = "DELETE FROM $db.".static::$table_name." WHERE ";
		
		// create cols
		$cols = array();
		
		foreach(static::$MVars as $var) {
			$vname = $var->name;
			$cols[count($cols)] = $var->data['name'] . " = '" . json_encode($this->$vname) . "'";
		}
		
		$sql = $sql . join(" AND ", $cols);
				
		// connects to the mysql server
		$con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_error($con));
		
		// execute delete
		$res = mysqli_query($con, $sql);
	}
	
	public static function all() {
		
		$dbinfo = static::$dbinfo;
		
		// gets the host
		$host = isset($dbinfo['host'])?$dbinfo['host']:"localhost";
		
		// gets the user
		$user = isset($dbinfo['user'])?$dbinfo['user']:"root";
		
		// gets the passwd
		$pass = isset($dbinfo['pass'])?$dbinfo['pass']:"";
		
		// gets the db
		$db = isset($dbinfo['db'])?$dbinfo['db']:"mvc";
		
		// start sql
		$sql = "SELECT * FROM $db.".static::$table_name;
		
		$con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_error($con));
		
		$res = mysqli_query($con, $sql);
		
		$ress = array();
		
		$class_name = get_called_class();
		
		while ($row = $res->fetch_array()) {
			$result = new $class_name();
			/*foreach(get_object_vars($row) as $var => $val) {
				$result->$var = json_decode($val);
			}*/
			foreach(static::$MVars as $var) {
				if(isset($row[$var->data['name']])) {
					$vname = $var->name;
					$result->$vname = $row[$var->data['name']];
				}
			}
			$ress[count($ress)] = $result;
		}
		
		return $ress;
		
	}
	
	public static function get($search) {
		
		$dbinfo = static::$dbinfo;
		
		// gets the host
		$host = isset($dbinfo['server'])?$dbinfo['server']:"localhost";
		
		// gets the user
		$user = isset($dbinfo['user'])?$dbinfo['user']:"root";
		
		// gets the passwd
		$pass = isset($dbinfo['pass'])?$dbinfo['pass']:"";
		
		// gets the db
		$db = isset($dbinfo['db'])?$dbinfo['db']:"mvc";
		
		// start sql
		$sql = "SELECT * FROM $db." . static::$table_name;
		
		$cols = array();
		
		foreach(static::$MVars as $var) {
			$vname = $var->name;
			if(isset($search[$vname]))
				$cols[count($cols)] = $var->data['name'] . " = '" . json_encode($search[$vname]) . "'";
		}
		
		$where_str = join(" AND ", $cols);
		
		$sql = $sql . (isset($where_str) ? (" WHERE " . $where_str) : "");
		
		$con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_error($con));
		
		$res = mysqli_query($con, $sql);
		
		$result = null;
		
		if($row = $res->fetch_array()) {
			$class_name = get_called_class();
			$result = new $class_name();
			foreach(static::$MVars as $var) {
				if(isset($row[$var->data['name']])) {
					$vname = $var->name;
					$result->$vname = $row[$var->data['name']];
				}
			}
		}
		
		return $result;
		
	}
	
}

class MVarDesc extends Object {
	public $name, $data;
	
	public function __construct($var, $md) {
		$this->name = $var;
		$this->data = $md;
	}
}
?>
MODEL;
file_put_contents(DIR . "lib/model.php", $contents);
echo "\nCreated lib/model.php";
$contents = <<<'ROUTER'
<?php

  if(!empty($_REQUEST['_method'])) {
    if(in_array(strtoupper($_REQUEST['_method']), array('PUT', 'DELETE'))) {
      $_SERVER['REQUEST_METHOD'] = strtoupper($_REQUEST['_method']);
    }
    if(strtoupper($_REQUEST['_method'])=="DEL") {
      $_SERVER['REQUEST_METHOD'] = "DELETE";
    }
  }

  include_once "sammy.php";
  
  class Router {
  	private static function handler($handler) {
  		global $rhandler;
  		$rhandler = $handler;
  		return returner(function() {
  				global $rhandler, $r, $sammy;
  				$r->format = $sammy->format;
  				$rhandler($r);
  		});
  	}
  	
    public static function get($url,$handler) {
      sammyGet($url,static::handler($handler));
    }
    
    public static function post($url,$handler) {
      sammyPost($url,static::handler($handler));
    }
    
    public static function ajax($url,$handler) {
      sammyAjax($url,static::handler($handler));
    }
    
    public static function put($url,$handler) {
      sammyPut($url,static::handler($handler));
    }
    
    public static function del($url,$handler) {
      sammyDelete($url,static::handler($handler));
    }
    
    public static function handle() {
      global $sammy;
      $sammy->run();
    }
    
    public function format($format, $handler) {
    	global $sammy;
    	$sammy->format($format, static::handler($handler));
    }
  }
  $r = new Router();
?>
ROUTER;
file_put_contents(DIR . "lib/router.php", $contents);
echo "\nCreated lib/router.php";
$base_url = dirname(getenv("SCRIPT_NAME"));
$contents = <<<HT
RewriteEngine On
RewriteBase $base_url

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
RewriteCond %{REQUEST_FILENAME} \.gitignore [OR]
RewriteCond %{REQUEST_FILENAME} README\.md
RewriteRule (.*) index.php/$1

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule (.*) index.php/$1
HT;
file_put_contents(DIR . ".htaccess", $contents);
echo "\nCreated .htaccess";
echo "</pre>";
?>