<?php
class Model {
  public $db;
  function __construct() {
    $m = new MongoClient("mongodb://127.0.0.1:27017");
    $db = $m->selectDB('collnet');
  }
}
?>
