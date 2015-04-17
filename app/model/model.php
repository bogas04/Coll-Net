<?php
class Model {
  public $db;
  function __construct() {
    try {
      $m = new MongoClient("mongodb://127.0.0.1:27017");
      $this->db = $m->selectDB('collnet');
    } catch(MongoConnectionException $e) {
      die('Error connecting to MongoDB server');
    } catch(MongoException $e) {
      die('Error: '.$e->getMessage());
    }
  }
}
?>
