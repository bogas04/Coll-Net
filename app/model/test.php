<?php
require('user.php');

try {

  $m = new MongoClient("mongodb://127.0.0.1:27017");
  $db = $m->selectDB('collnet');
  $cursor = (new MongoCollection($db, 'users'))->find();
  foreach($cursor as $u) {
    print_r($u);
  }
  
}
catch(Exception $e) {
  echo $e.getMessage();
}

