<?php
include_once "lib/base.php";
Router::get("/hello",function() {
		?>
    Hi, Hello, Hola, Bonjure
    <?php
});
Router::get("/",function() {
		?>
    Home
    <?php
});

Router::put("/",function() {
    return "Added.";
});

Router::del("/",function() {
    return "Deleted.";
});

Router::get('/views/test',function($r) {
		$r->format("json", function() {
				echo json_encode(array(
					"hi" => array(
						"name" => "Drew"
						)
					));
		});
		$htm = returner(function() {
			global $view;
			$view->hi_name = "Drew";
			view('test');
		});
		$r->format('html', $htm);
});

Router::get('/controller',controller('index'));

Router::get('/controller/other', action('index','other'));

Router::get('/controller/model', action('indexLocal','model'));

Router::handle();
?>