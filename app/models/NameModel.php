<?php
	class NameModel extends Model {
		var $name;
		var $type;
	}
	NameModel::$MVars = array(
			new MVarDesc("name",array(
				"name" => "title",
				"type" => "VARCHAR(10)"
				)),
			new MVarDesc("type",array(
				"name" => "classer",
				"type" => "VARCHAR(100)"
				))
			);
	NameModel::$PKey = NameModel::$MVars[0];
?>
