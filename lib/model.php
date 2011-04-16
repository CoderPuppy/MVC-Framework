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
		
		// connects to the mysql server
		$con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_error($con));
		
		// execute insert
		$res = mysqli_query($con, $sql);
		
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
				
		// connects to the mysql server
		$con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_error($con));
		
		// execute insert
		$res = mysqli_query($con, $sql);
		
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
					$result->$vname = json_decode($row[$var->data['name']]);
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
					$result->$vname = json_decode($row[$var->data['name']]);
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
