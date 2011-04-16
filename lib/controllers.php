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