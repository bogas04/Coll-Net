<?php
class Model
{
  // TODO: Create a property db
	function __construct()
	{
		$m = new MongoClient();
		echo "Connection to database successful"; // TODO : Use exceptions
		$dbname = $m->selectDB('collnet');
    // TODO: Set this as a property in this class
    
	}
}
?>
