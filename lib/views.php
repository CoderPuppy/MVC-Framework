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