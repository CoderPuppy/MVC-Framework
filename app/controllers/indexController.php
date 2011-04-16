<?php
	class IndexController extends AppController {
		function index() {
			echo "Hi, ".(isset($_REQUEST['name'])?$_REQUEST['name']:"You")."!!!";
		}
		
		function other() {
			$this->hi_name = "Drew";
			$this->renderView('test');
		}
		
		function model($something) {
			global $r;
			include_once MODEL_ROOT . "NameModel.php";
			
			NameModel::open(array(
				"db" => "db",
				"server" => "host",
				"user" => "user",
				"pass" => "passwd"
				));
			
			$name = new NameModel();
			
			$name->name = "hi";
			
			$name->type = array(
				new stdClass(),
				$this,
				$r,
				"higher",
				$something
				);
			
			$is_there = $name->isSaved();
			
			// debug $is_there
			echo "<pre>";
			echo "\$is_there:\n";
			print_r($is_there);
			echo "</pre>";
			
			$name->add();
			
			$is_there = $name->isSaved();
			
			// debug $is_there
			echo "<pre>";
			echo "\$is_there:\n";
			print_r($is_there);
			echo "</pre>";
			
			$all = NameModel::all();
			
			// debug $all
			echo "<pre>";
			echo "\$all:\n";
			print_r($all);
			echo "</pre>";
			
			$name->update(NameModel::$MVars[0], "Person");
			
			$all = NameModel::all();
			
			// debug $all
			echo "<pre>";
			echo "\$all:\n";
			print_r($all);
			echo "</pre>";
			
			$hi = NameModel::get(array(
				"name" => "Person"
				));
			
			// debug $hi
			echo "<pre>";
			echo "\$hi:\n";
			print_r($hi);
			echo "</pre>";
			
		}
	}
?>
