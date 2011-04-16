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