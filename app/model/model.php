<?php
class Model
{
	private $db;
	function __construct()
	{
	//	try
	//	{
		  $m = new MongoClient("mongodb://127.0.0.1:27017");
		  $db = $m->selectDB('collnet');
		  //$m->close();
	//	}
	//	catch(MongoConnectionException $e)
	//	{
	//	  die('Error connecting to MongoDB server');
	//	}
	//	catch(MongoException $e)
	//	{
	//	  die('Error: '.$e->getMessage());
	//	}
	}
}
?>
