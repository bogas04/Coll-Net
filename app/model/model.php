<?php
class Model
{
	function __construct()
	{
		$m = new MongoClient();
		echo "Connection to database successful";
		$dbname = $m->selectDB('collnet');
	}
}
?>
