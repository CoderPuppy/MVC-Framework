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
				return $rhandler($r);
		});
	}
	
	private static function redirectHandler($to) {
		global $rto;
		$rto = $to;
		return returner(function() {
				global $rto;
				header('Location: ' . $rto);
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
	
	public static function redirect($type, $url, $to) {
		$type = strtoupper($type);
		if($to[0] != "/") {
			$to = ROOT_PATH . $to;
		}
		switch($type)
		{
		case "GET":
			static::get($url, static::redirectHandler($to));
			break;
		case "POST":
			static::post($url, static::redirectHandler($to));
			break;
		case "PUT":
			static::put($url, static::redirectHandler($to));
			break;
		case "DELETE":
		case "DEL":
			static::del($url, static::redirectHandler($to));
			break;
		case "AJAX":
			static::ajax($url, static::redirectHandler($to));
			break;
		case "ALL":
			static::get($url, static::redirectHandler($to));
			static::post($url, static::redirectHandler($to));
			static::put($url, static::redirectHandler($to));
			static::del($url, static::redirectHandler($to));
			static::ajax($url, static::redirectHandler($to));
			break;
		}
	}
}
$r = new Router();
?>